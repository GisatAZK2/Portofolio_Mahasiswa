<header
    class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50 transition-all">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            <!-- Mobile: Hamburger + Search Icon -->
            <div class="flex items-center gap-4 lg:hidden">
                <button id="toggle-sidebar" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <svg id="sidebar-hamburger" class="w-7 h-7 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="sidebar-close" class="w-7 h-7 hidden transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <button id="toggle-search-mobile" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- MOBILE TITLE  -->
            <div class="absolute left-1/2 -translate-x-1/2 lg:hidden">
                <a href="{{ route('dashboard') }}">
                    <h1 class="text-sm font-semibold text-indigo-600 tracking-wide">
                        PORTOFOLIO MAHASISWA
                    </h1>
                </a>
            </div>

            <!-- DESKTOP SEARCH -->
            <div id="search-container" class="hidden lg:flex lg:items-center lg:gap-3 w-full max-w-5xl mx-auto">
                <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2.5 w-full">

                    <!-- Search Input -->
                    <div class="relative grow min-w-0">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 text-gray-500 dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-100 text-sm transition shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm"
                            placeholder="Cari...">
                    </div>

                    <a href="{{ route('search') }}"
                        class="bg-white/60 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-700/50 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-500 focus:ring-2 focus:ring-gray-300 transition text-sm font-medium backdrop-blur-sm border border-gray-300/50">
                        Reset
                    </a>

                    <!-- Filters -->
                    <select name="jurusan"
                        class="block border border-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-700/50 rounded-lg py-2.5 px-3 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400 min-w-[140px] lg:min-w-[160px]">
                        <option class="dark:bg-gray-800 dark:text-gray-200" data-translate="filter_jurusan"
                            data-translate-page="search" value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option class="dark:bg-gray-800 dark:text-gray-200" value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ Str::limit($jurusan->nama_jurusan, 20) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="keahlian"
                        class="block border  border-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-700/50 rounded-lg py-2.5 px-3 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400 min-w-[140px] lg:min-w-[160px]">
                        <option class="dark:bg-gray-800 dark:text-gray-200" data-translate="filter_keahlian"
                            data-translate-page="search" value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option class="dark:bg-gray-800 dark:text-gray-200" value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ Str::limit($keahlian->nama_keahlian, 20) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="angkatan"
                        class="block border border-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-700/50 rounded-lg py-2.5 px-3 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400 min-w-[120px] lg:min-w-[140px]">
                        <option class="dark:bg-gray-800 dark:text-gray-200 dark:border-gray-900"
                            data-translate="filter_angkatan" data-translate-page="search" value="">Semua Angkatan
                        </option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option class="dark:bg-gray-800 dark:text-gray-200 dark:border-gray-900"
                                value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>
                                {{ $angkatan->nama_angkatan }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Buttons -->
                    <div  class="flex gap-2 shrink-0">
                        <button  type="submit"
                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition text-sm font-medium flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span data-translate="search" data-translate-page="search"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MOBILE Search Dropdown -->
    <div id="mobile-search-dropdown"
        class="lg:hidden bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 overflow-hidden transition-all duration-300 ease-in-out max-h-0">
        <div class="px-4 py-5 space-y-5 sm:px-6">
            <form method="GET" action="{{ route('search') }}" class="space-y-4">

                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="w-full pl-11 pr-4 py-3 text-sm border border-gray-300/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 placeholder-gray-500 shadow-sm bg-white/80 backdrop-blur-sm"
                        placeholder="Cari mahasiswa, proyek, portofolio...">
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <select name="jurusan"
                        class="block w-full border border-gray-300/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    <select name="keahlian"
                        class="block w-full border border-gray-300/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ $keahlian->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>

                    <select name="angkatan"
                        class="block w-full border border-gray-300/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 backdrop-blur-sm focus:ring-indigo-400 focus:border-indigo-400 sm:col-span-2">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>
                                {{ $angkatan->nama_angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div  class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <button data-translate="search" data-translate-page="search" type="submit"
                        class="flex-1 bg-indigo-600 text-white py-3.5 rounded-lg text-sm font-medium hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        
                            <span data-translate="search" data-translate-page="search"></span>
                    </button>
                    <a href="{{ route('search') }}"
                        class="flex-1 bg-white/60 text-gray-700 py-3.5 rounded-lg text-sm font-medium hover:bg-gray-100/80 focus:ring-2 focus:ring-gray-300 transition flex items-center justify-center border border-gray-300/50 backdrop-blur-sm">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>
</header>