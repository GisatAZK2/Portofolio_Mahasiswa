@extends('Layout.Layout')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50/50 ">
    <div class=" mx-auto">

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Cover + Avatar -->
            <div class="relative h-48 bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-600">
                <div class="absolute -bottom-16 left-1/2 -translate-x-1/2">
                    <div class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden ring-1 ring-gray-200/50">
                        @if (Auth::user()->photo_profile)
                            <img 
                                src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                alt="{{ Auth::user()->nama_mahasiswa ?? 'Profile' }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-semibold">
                                {{ strtoupper(substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten utama -->
            <div class="pt-20 px-6 pb-10 sm:px-10">

                <!-- Nama -->
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-2">
                    {{ Auth::user()->nama_mahasiswa ?? 'Mahasiswa' }}
                </h2>
                <p class="text-center text-gray-500 text-sm mb-8">
                    {{ Auth::user()->username ? '@' . Auth::user()->username : '' }}
                </p>

                <!-- Grid informasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto">

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                        <p class="text-base font-medium text-gray-800 break-all">
                            {{ Auth::user()->email ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jurusan</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ Auth::user()->jurusan->nama_jurusan ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Keahlian / Program Studi</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ Auth::user()->keahlian->nama_keahlian ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition sm:col-span-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status Akun</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <p class="text-base font-medium {{ Auth::user()->is_active ? 'text-green-700' : 'text-red-700' }}">
                                {{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Tombol aksi -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                  

                    <button 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="px-6 py-3 bg-white text-red-600 font-medium rounded-lg border border-red-200 hover:bg-red-50 transition">
                        Keluar
                    </button>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

            </div>
        </div>

        <!-- Footer kecil -->
        <div class="mt-8 text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
        </div>

    </div>
</div>
@endsection