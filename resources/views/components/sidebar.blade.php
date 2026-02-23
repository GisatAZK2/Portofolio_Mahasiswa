<aside class="w-64 bg-white border-r border-gray-200 text-gray-800 h-screen flex flex-col shadow-sm">

    <!-- Logo -->
    <div class="px-6 py-6 border-b border-gray-200 flex items-center justify-center">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md">
            <img src="{{ asset('assets/profile.jpg') }}" alt="Logo" class="w-20 h-20 rounded-full logo-zoom" style="cursor:pointer;">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

        <!-- Dashboard (selalu ada) -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-500/40 border border-blue-300/50 shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Portfolio -->
        <div class="space-y-1">
            <button onclick="togglePortfolioMenu()" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('portofolio.*') ? 'bg-blue-500/40 border border-blue-300/50 shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Portfolio</span>
                <svg id="portfolioArrow" class="ml-auto w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7-7m0 0l-7 7m7-7v12"></path>
                </svg>
            </button>

            <div id="portfolioMenu" class="pl-4 space-y-1 hidden">

                <!-- Selalu boleh dilihat (read) -->
                <a href="{{ route('portofolio.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span>Lihat Semua Portfolio</span>
                </a>

                @auth
                    <!-- Hanya muncul kalau sudah login -->
                    <a href="{{ route('portofolio.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Portfolio Baru</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Portfolio Saya</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Menu lain yang butuh login -->
        @auth
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Project</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25m20-11.197C21.5 6.253 17 10.998 17 17.25m0-13V6a2 2 0 10-4 0v.253m4 0C13.5 5.482 12.8 5 12 5c-.8 0-1.5.482-1.5 1.253v13M12 21a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                <span>Learning Corner</span>
            </a>

            <a href="{{ route('settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('settings') ? 'bg-blue-500/40 border border-blue-300/50 shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Settings</span>
            </a>
        @endauth

    </nav>

    <!-- Bagian bawah sidebar -->
    <div class="px-4 py-4 border-t border-gray-200">
        @auth
            <!-- Sudah login -->
            <div class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-800">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->nama_mahasiswa }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded transition">
                    Logout
                </button>
            </form>
        @else
            <!-- Belum login -->
            <div class="space-y-2">
                <a href="{{ route('login') }}" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block w-full px-4 py-2 border border-gray-300 text-center rounded-lg hover:bg-gray-50 transition">
                    Daftar
                </a>
            </div>
        @endauth
    </div>

</aside>