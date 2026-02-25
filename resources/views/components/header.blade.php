<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- PATH  -->
            <div class="flex items-center gap-4 lg:hidden">
                <button id="toggle-sidebar" class="text-gray-700 focus:outline-none">
                    <svg id="sidebar-hamburger" class="w-8 h-8 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="sidebar-close" class="w-8 h-8 hidden transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <button id="toggle-search-mobile" class="text-gray-700 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Search bar desktop (lg ke atas) -->
            <div id="search-container" class="hidden lg:block w-full max-w-4xl mx-auto">
                <form method="GET" action="{{ route('search') }}" class="flex items-center gap-4">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full pl-14 pr-5 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 placeholder-gray-500 text-base transition shadow-sm"
                            placeholder="Cari mahasiswa, project, portofolio..."
                        >
                    </div>

                    <select name="jurusan" class="block border border-gray-300 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 text-base bg-white min-w-[180px]">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ Str::limit($jurusan->nama_jurusan, 28) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="keahlian" class="block border border-gray-300 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 text-base bg-white min-w-[180px]">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ Str::limit($keahlian->nama_keahlian, 28) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-3 shrink-0">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3.5 rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition text-base font-medium flex items-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari
                        </button>
                        <a href="{{ route('search') }}" class="bg-gray-100 text-gray-700 px-6 py-3.5 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition text-base font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Search Dropdown -->
    <div id="mobile-search-dropdown" class="hidden lg:hidden bg-white border-b border-gray-200 overflow-hidden transition-all duration-300 ease-in-out max-h-0">
        <div class="px-5 py-6 space-y-6">
            <form method="GET" action="{{ route('search') }}" class="space-y-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-7 w-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="w-full pl-16 pr-6 py-5 text-lg border-2 border-gray-300 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:shadow-lg transition shadow-md placeholder-gray-500 text-gray-800"
                           placeholder="Cari mahasiswa, project, portofolio...">
                </div>

                <div class="space-y-5">
                    <select name="jurusan" class="block w-full border-2 border-gray-300 rounded-2xl py-5 px-5 text-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-md transition appearance-none">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    <select name="keahlian" class="block w-full border-2 border-gray-300 rounded-2xl py-5 px-5 text-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-md transition appearance-none">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ $keahlian->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:gap-5">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-5 rounded-2xl text-lg font-semibold hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition flex items-center justify-center gap-3 shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Sekarang
                    </button>
                    <a href="{{ route('search') }}" class="flex-1 bg-gray-200 text-gray-800 py-5 rounded-2xl text-lg font-semibold hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition flex items-center justify-center shadow-md">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

</header>


