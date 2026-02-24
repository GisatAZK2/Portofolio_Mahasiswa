<header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
    <div class="px-4 sm:px-6 lg:px-8 py-3 flex flex-col lg:flex-row justify-between items-center gap-4">

        <!-- Bagian kiri: Hamburger + Logo/Brand -->
        <div class="flex items-center justify-between w-full lg:w-auto">
            <!-- Hamburger (mobile only) -->
            <button id="toggle-sidebar" class="lg:hidden text-gray-700 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>

        <!-- Search & Filter Form (tengah / full di mobile) -->
        <div class="w-full lg:max-w-4xl">
            <form method="GET" action="{{ route('search') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                
                <!-- Kata Kunci -->
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 placeholder-gray-400"
                        placeholder="Cari mahasiswa, project, portofolio, learning..."
                    >
                </div>

                <!-- Jurusan -->
                <div class="w-full sm:w-48">
                    <select name="jurusan" class="block w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Keahlian -->
                <div class="w-full sm:w-48">
                    <select name="keahlian" class="block w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>
                                {{ $keahlian->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex gap-2 sm:gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>

                    <a href="{{ route('search') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Bagian kanan: User menu / notifikasi (opsional) -->
        <div class="hidden lg:flex items-center space-x-4">
            <!-- Notifikasi, profile, dll bisa ditambahkan di sini -->
        </div>

    </div>

    <!-- Ringkasan pencarian aktif (opsional, muncul di bawah search bar) -->
    @if(Route::currentRouteName() === 'search' && (request('q') || request('jurusan') || request('keahlian')))
        <div class="px-6 py-2 bg-gray-50 border-t text-sm text-gray-600">
            Menampilkan hasil untuk 
            @if(request('q')) "<strong>{{ request('q') }}</strong>" @endif
            @if(request('jurusan') && isset($jurusanList)) di <strong>{{ $jurusanList->find(request('jurusan'))?->nama_jurusan }}</strong> @endif
            @if(request('keahlian') && isset($keahlianList)) dengan keahlian <strong>{{ $keahlianList->find(request('keahlian'))?->nama_keahlian }}</strong> @endif
        </div>
    @endif
</header>