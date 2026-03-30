@extends('Layout.Layout')
@section('title', 'Kelola Pengguna - Admin')
@section('content')

<h1> SELAMAT DATANG: </h1>

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
        @endauth
    @endsection
