<aside id="sidebar"
      class="fixed inset-y-0 left-0 w-62 z-50 bg-white border-r border-gray-200 shadow-xl
             transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out
             lg:static lg:inset-auto lg:shadow-sm
             flex flex-col overflow-hidden">
    
    <div class="px-6 py-6 mt-10 border-b border-gray-200 flex items-center justify-between shrink-0">
        <div class="flex-1 flex justify-center lg:justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center shadow-md">
                <img id="logo-zoom" src="{{ asset('assets/Logo.svg') }}" alt="Logo"
                     class="w-20 h-20 rounded-full object-cover">
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        
    <!-- Dashboard -->
      @auth 
        <a href="{{ route('dashboard.me') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ request()->routeIs('dashboard.me') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4"/>
            </svg>
            <span class="font-medium">My Dashboard</span>
        
        @endauth
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4"/>
            </svg>
            <span class="font-medium">Dashboard</span>
            </a>

        <!-- ================== PROJECT ================== -->
        @guest
           <a href="{{ route('project.project_user') }}"
       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                  {{ request()->routeIs('project.project_user') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}"> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Project Mahasiswa</span>
            </a>
        @else
            <div class="space-y-1">
                <button onclick="toggleDropdown('project')"
                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                               {{ request()->routeIs('project.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium flex-1 text-left">Project</span>
                    <svg id="projectArrow" class="w-4 h-4 transition-transform duration-300 {{ request()->routeIs('project.*') ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="projectMenu" class="pl-5 space-y-1 {{ request()->routeIs('project.*') ? '' : 'hidden' }} mt-1">
                    <a href="{{ route('project.index') }}"
                       class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                              {{ request()->routeIs('project.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <span>Lihat Project Saya</span>
                    </a>

                    <a href="{{ route('project.create') }}"
                       class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                              {{ request()->routeIs('project.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Project Baru</span>
                    </a>
                </div>
            </div>
        @endguest

        <!-- Learning Corners - hanya untuk user yang sudah login -->
        @auth
        <div class="space-y-1">
            <button onclick="toggleDropdown('learningCorner')"
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                           {{ request()->routeIs('learning-corner.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25m20-11.197C21.5 6.253 17 10.998 17 17.25m0-13V6a2 2 0 10-4 0v.253m4 0C13.5 5.482 12.8 5 12 5c-.8 0-1.5.482-1.5 1.253v13M12 21a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
                <span class="font-medium flex-1 text-left">Learning Corners</span>
                <svg id="learningCornerArrow" class="w-4 h-4 transition-transform duration-300 {{ request()->routeIs('learning-corner.*') ? 'rotate-180' : '' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="learningCornerMenu" class="pl-5 space-y-1 {{ request()->routeIs('learning-corner.*') ? '' : 'hidden' }} mt-1">
                <a href="{{ route('learning-corner.index') }}"
                   class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('learning-corner.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Lihat Catatan</span>
                </a>
                <a href="{{ route('learning-corner.create') }}"
                   class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('learning-corner.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Catatan Baru</span>
                </a>
            </div>
        </div>
        @endauth

         <!-- Sertifikat - hanya untuk user yang sudah login -->
          @auth
      <div class="space-y-1">
    <button onclick="toggleDropdown('sertifikat')"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs('sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
  <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
  <path d="M8 2v4"></path>
  <path d="M16 2v4"></path>
  <path d="M3 10h18"></path>
  <circle cx="12" cy="14" r="2"></circle>
</svg>
        <span class="font-medium flex-1 text-left">Sertifikat</span>
        <svg id="sertifikatArrow" class="w-4 h-4 transition-transform duration-300 {{ request()->routeIs('sertifikat.*') ? 'rotate-180' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div id="sertifikatMenu" class="pl-5 space-y-1 {{ request()->routeIs('sertifikat.*') ? '' : 'hidden' }} mt-1">
            <a href="{{ route('sertifikat.index') }}"
                   class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('sertifikat.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    <span>Lihat Sertifikat</span>
                </a>
                <a href="{{ route('sertifikat.create') }}"
                   class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->routeIs('sertifikat.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Sertifikat Baru</span>
                </a>
            </div>
        </div>
        @endauth


    </nav>

    <!-- FOOTER -->
    <div class="px-4 py-5 border-t border-gray-200 bg-gray-50 shrink-0">
        @auth
            <a href="{{ route('profile') ?? '/profile' }}" class="block hover:bg-gray-100 rounded-lg transition p-2 -mx-2">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0">
                        @if (Auth::user()->photo_profile)
                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                 alt="{{ Auth::user()->nama_mahasiswa }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nama_mahasiswa }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                    </div>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition font-medium mt-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        @else

            <div class="text-center text-sm text-gray-500">
                Login untuk mengakses fitur lengkap
                 <div class="mt-3 flex">
                    <a href="{{ route('login') }}" class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition font-medium text-sm">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 px-4 py-2.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition font-medium text-sm">
                        Register
                    </a>
            </div>
               
        @endauth
    </div>

</aside>