<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            <!-- Kiri: Hamburger + Search Icon (mobile only) -->
            <div class="flex items-center gap-3 lg:hidden">
                <button id="toggle-sidebar" class="text-gray-700 focus:outline-none -ml-1">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <button id="toggle-search-mobile" class="text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Logo kecil opsional (jika mau) -->
            <!-- <div class="text-lg font-bold text-indigo-700 lg:hidden">AmbaShop</div> -->

            <!-- Search & Filter - selalu tampil di desktop, disembunyikan di mobile -->
            <div id="search-container" class="hidden lg:block w-full max-w-3xl mx-auto">
                <form method="GET" action="{{ route('search') }}" class="flex items-center gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 placeholder-gray-500 text-sm transition"
                            placeholder="Cari mahasiswa, project, portofolio..."
                        >
                    </div>

                    <!-- Jurusan -->
                    <select
                        name="jurusan"
                        class="block border border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 text-sm bg-white min-w-[140px]"
                    >
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ Str::limit($jurusan->nama_jurusan, 28) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Keahlian -->
                    <select
                        name="keahlian"
                        class="block border border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 text-sm bg-white min-w-[140px]"
                    >
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ Str::limit($keahlian->nama_keahlian, 28) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Tombol -->
                    <div class="flex gap-2 shrink-0">
                        <button
                            type="submit"
                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition text-sm font-medium flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari
                        </button>
                        <a
                            href="{{ route('search') }}"
                            class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition text-sm font-medium"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Search Dropdown (slide down) -->
    <div id="mobile-search-dropdown" class="hidden lg:hidden bg-white border-b border-gray-200 overflow-hidden transition-all duration-300 ease-in-out max-h-0">
        <div class="px-4 py-5 space-y-4">
            <form method="GET" action="{{ route('search') }}" class="space-y-4">
                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 placeholder-gray-500"
                        placeholder="Cari mahasiswa, project, portofolio..."
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Jurusan -->
                    <select name="jurusan" class="block w-full border border-gray-300 rounded-lg py-3 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ Str::limit($jurusan->nama_jurusan, 28) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Keahlian -->
                    <select name="keahlian" class="block w-full border border-gray-300 rounded-lg py-3 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ Str::limit($keahlian->nama_keahlian, 28) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition font-medium flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    <a
                        href="{{ route('search') }}"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition font-medium text-center"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan pencarian (tetap seperti semula) -->
    @if(Route::currentRouteName() === 'search' && (request('q') || request('jurusan') || request('keahlian')))
        <div class="px-4 sm:px-6 py-2 bg-gray-50 border-t text-xs sm:text-sm text-gray-600">
            <span class="font-medium">Hasil untuk:</span>
            @if(request('q')) “{{ request('q') }}” @endif
            @if(request('jurusan') && isset($jurusanList))
                jurusan <strong>{{ $jurusanList->find(request('jurusan'))?->nama_jurusan }}</strong>
            @endif
            @if(request('keahlian') && isset($keahlianList))
                keahlian <strong>{{ $keahlianList->find(request('keahlian'))?->nama_keahlian }}</strong>
            @endif
        </div>
    @endif
</header>