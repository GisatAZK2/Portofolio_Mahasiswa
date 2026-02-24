<header class="bg-white shadow-md border-b border-gray-200">
    <div class="px-6 py-4 flex justify-between items-center">
        <!-- Hamburger (hanya mobile) -->
        <button id="toggle-sidebar" class="lg:hidden text-gray-700 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <div class="flex items-center space-x-4">
            <!-- Search Bar -->
            <form action="{{ route('search') }}" method="GET"
                  class="hidden md:flex items-center bg-gray-100 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                    </path>
                </svg>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search mahasiswa, project..."
                    class="bg-transparent ml-2 outline-none w-48 md:w-64 text-gray-700">
            </form>
        </div>
    </div>
</header>   