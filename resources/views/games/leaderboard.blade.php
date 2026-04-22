@extends('Layout.Layout')

@section('title', autoTranslate('Leaderboard'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
    <div class="container mx-auto px-4 py-6 md:py-8 max-w-7xl">
        
        <!-- Header with Description -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8">
            <div class="md:w-2/3">
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-yellow-500 to-orange-500 bg-clip-text text-transparent">
                    {{ autoTranslate('Leaderboard') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ autoTranslate('Papan peringkat pemain berdasarkan total skor tertinggi dari semua permainan.') }}
                </p>
            </div>
            
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Podium Section (Top 3) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6 text-center">
                            🏆 {{ autoTranslate('Pemain Teratas') }}
                        </h2>
                        
                        @php
                            $top3 = $games->slice(0, 3);
                        @endphp
                        
                        @if($top3->count() > 0)
                            <!-- Mobile Podium -->
                            <div class="block lg:hidden">
                                <div class="relative flex items-end justify-center gap-4 min-h-[280px]">
                                    
                                    <!-- 2nd Place -->
                                    @if($top3->count() >= 2)
                                        @php $item2 = $top3[1]; $user2 = $item2['user']; @endphp
                                        <div class="text-center flex-1 pb-8">
                                            <div class="relative inline-block">
                                                <div class="w-20 h-20 rounded-full border-3 border-[#009BD6] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-lg">
                                                    @if($user2 && $user2->photo_profile && file_exists(public_path('storage/' . $user2->photo_profile)))
                                                        <img src="{{ asset('storage/' . $user2->photo_profile) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-gray-500 font-bold text-2xl">{{ strtoupper(substr($user2->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-[#009BD6] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">2</div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="text-gray-800 dark:text-white font-semibold text-xs truncate max-w-[100px] mx-auto">
                                                    {{ $user2->nama_mahasiswa ?? 'User' }}
                                                </div>
                                                <div class="text-[#009BD6] font-bold text-base mt-1">{{ number_format($item2['total_score']) }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $item2['games_played'] }} game</div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 1st Place -->
                                    @if($top3->count() >= 1)
                                        @php $item1 = $top3[0]; $user1 = $item1['user']; @endphp
                                        <div class="text-center flex-1 -mt-8">
                                            <div class="relative inline-block">
                                                <div class="w-24 h-24 rounded-full border-3 border-[#FFAA00] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-xl">
                                                    @if($user1 && $user1->photo_profile && file_exists(public_path('storage/' . $user1->photo_profile)))
                                                        <img src="{{ asset('storage/' . $user1->photo_profile) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-gray-500 font-bold text-3xl">{{ strtoupper(substr($user1->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                                                    <svg class="w-7 h-7" viewBox="0 0 28 21" fill="none">
                                                        <path d="M27.3004 4.76325C27.0255 4.54106 26.6916 4.39816 26.3369 4.35086C25.9821 4.30355 25.6207 4.35375 25.294 4.49572L19.2148 7.11292L15.6826 0.947954C15.5138 0.660129 15.2691 0.420767 14.9737 0.254247C14.6782 0.0877275 14.3424 0 14.0006 0C13.6587 0 13.3229 0.0877275 13.0275 0.254247C12.732 0.420767 12.4874 0.660129 12.3186 0.947954L8.78635 7.11292L2.70711 4.49572C2.37972 4.35396 2.01791 4.30369 1.66256 4.35061C1.30722 4.39752 0.972515 4.53974 0.69629 4.7612C0.420064 4.98265 0.213332 5.2745 0.099465 5.60375C-0.0144023 5.93301 -0.0308642 6.28654 0.0519402 6.62438L3.10358 19.2218C3.16193 19.4657 3.27082 19.6956 3.42364 19.8976C3.57646 20.0995 3.77004 20.2693 3.99264 20.3967C4.29401 20.5713 4.63859 20.6638 4.98983 20.6642C5.16057 20.6639 5.33043 20.6404 5.49443 20.5944C11.0568 19.1055 16.9323 19.1055 22.4947 20.5944C23.0026 20.7237 23.5427 20.6526 23.9965 20.3967C24.2205 20.2709 24.4151 20.1016 24.5681 19.8994C24.7211 19.6972 24.8292 19.4665 24.8855 19.2218L27.9492 6.62438C28.0311 6.28644 28.0137 5.93308 27.8991 5.6042C27.7844 5.27532 27.5771 4.98404 27.3004 4.76325Z" fill="#FFAA00"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="text-gray-800 dark:text-white font-bold text-sm truncate max-w-[120px] mx-auto">
                                                    {{ $user1->nama_mahasiswa ?? 'User' }}
                                                </div>
                                                <div class="text-[#FFAA00] font-bold text-xl mt-1">{{ number_format($item1['total_score']) }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $item1['games_played'] }} game</div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 3rd Place -->
                                    @if($top3->count() >= 3)
                                        @php $item3 = $top3[2]; $user3 = $item3['user']; @endphp
                                        <div class="text-center flex-1 pb-8">
                                            <div class="relative inline-block">
                                                <div class="w-20 h-20 rounded-full border-3 border-[#00D95F] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-lg">
                                                    @if($user3 && $user3->photo_profile && file_exists(public_path('storage/' . $user3->photo_profile)))
                                                        <img src="{{ asset('storage/' . $user3->photo_profile) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-gray-500 font-bold text-2xl">{{ strtoupper(substr($user3->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-[#00D95F] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">3</div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="text-gray-800 dark:text-white font-semibold text-xs truncate max-w-[100px] mx-auto">
                                                    {{ $user3->nama_mahasiswa ?? 'User' }}
                                                </div>
                                                <div class="text-[#00D95F] font-bold text-base mt-1">{{ number_format($item3['total_score']) }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $item3['games_played'] }} game</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Desktop Podium -->
                            <div class="hidden lg:block">
                                <div class="relative">
                                    <div class="flex flex-col items-center justify-center gap-6">
                                        <!-- 2nd Place -->
                                        @if($top3->count() >= 2)
                                            @php $item2 = $top3[1]; $user2 = $item2['user']; @endphp
                                            <div class="text-center w-full">
                                                <div class="relative inline-block">
                                                    <div class="w-20 h-20 rounded-full border-3 border-[#009BD6] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-lg">
                                                        @if($user2 && $user2->photo_profile && file_exists(public_path('storage/' . $user2->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user2->photo_profile) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <span class="text-gray-500 font-bold text-2xl">{{ strtoupper(substr($user2->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="absolute -top-2 -right-2 w-7 h-7 bg-[#009BD6] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">2</div>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="text-gray-800 dark:text-white font-semibold text-sm truncate max-w-[120px] mx-auto">
                                                        {{ $user2->nama_mahasiswa ?? 'User' }}
                                                    </div>
                                                    <div class="text-[#009BD6] font-bold text-lg mt-1">{{ number_format($item2['total_score']) }}</div>
                                                    <div class="text-xs text-gray-400">{{ $item2['games_played'] }} game</div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- 1st Place -->
                                        @if($top3->count() >= 1)
                                            @php $item1 = $top3[0]; $user1 = $item1['user']; @endphp
                                            <div class="text-center w-full">
                                                <div class="relative inline-block">
                                                    <div class="w-28 h-28 rounded-full border-3 border-[#FFAA00] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-xl">
                                                        @if($user1 && $user1->photo_profile && file_exists(public_path('storage/' . $user1->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user1->photo_profile) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <span class="text-gray-500 font-bold text-3xl">{{ strtoupper(substr($user1->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                                                        <svg class="w-8 h-8" viewBox="0 0 28 21" fill="none">
                                                            <path d="M27.3004 4.76325C27.0255 4.54106 26.6916 4.39816 26.3369 4.35086C25.9821 4.30355 25.6207 4.35375 25.294 4.49572L19.2148 7.11292L15.6826 0.947954C15.5138 0.660129 15.2691 0.420767 14.9737 0.254247C14.6782 0.0877275 14.3424 0 14.0006 0C13.6587 0 13.3229 0.0877275 13.0275 0.254247C12.732 0.420767 12.4874 0.660129 12.3186 0.947954L8.78635 7.11292L2.70711 4.49572C2.37972 4.35396 2.01791 4.30369 1.66256 4.35061C1.30722 4.39752 0.972515 4.53974 0.69629 4.7612C0.420064 4.98265 0.213332 5.2745 0.099465 5.60375C-0.0144023 5.93301 -0.0308642 6.28654 0.0519402 6.62438L3.10358 19.2218C3.16193 19.4657 3.27082 19.6956 3.42364 19.8976C3.57646 20.0995 3.77004 20.2693 3.99264 20.3967C4.29401 20.5713 4.63859 20.6638 4.98983 20.6642C5.16057 20.6639 5.33043 20.6404 5.49443 20.5944C11.0568 19.1055 16.9323 19.1055 22.4947 20.5944C23.0026 20.7237 23.5427 20.6526 23.9965 20.3967C24.2205 20.2709 24.4151 20.1016 24.5681 19.8994C24.7211 19.6972 24.8292 19.4665 24.8855 19.2218L27.9492 6.62438C28.0311 6.28644 28.0137 5.93308 27.8991 5.6042C27.7844 5.27532 27.5771 4.98404 27.3004 4.76325Z" fill="#FFAA00"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="text-gray-800 dark:text-white font-bold text-base truncate max-w-[150px] mx-auto">
                                                        {{ $user1->nama_mahasiswa ?? 'User' }}
                                                    </div>
                                                    <div class="text-[#FFAA00] font-bold text-2xl mt-1">{{ number_format($item1['total_score']) }}</div>
                                                    <div class="text-xs text-gray-400">{{ $item1['games_played'] }} game</div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- 3rd Place -->
                                        @if($top3->count() >= 3)
                                            @php $item3 = $top3[2]; $user3 = $item3['user']; @endphp
                                            <div class="text-center w-full">
                                                <div class="relative inline-block">
                                                    <div class="w-20 h-20 rounded-full border-3 border-[#00D95F] overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-lg">
                                                        @if($user3 && $user3->photo_profile && file_exists(public_path('storage/' . $user3->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user3->photo_profile) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <span class="text-gray-500 font-bold text-2xl">{{ strtoupper(substr($user3->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="absolute -top-2 -right-2 w-7 h-7 bg-[#00D95F] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">3</div>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="text-gray-800 dark:text-white font-semibold text-sm truncate max-w-[120px] mx-auto">
                                                        {{ $user3->nama_mahasiswa ?? 'User' }}
                                                    </div>
                                                    <div class="text-[#00D95F] font-bold text-lg mt-1">{{ number_format($item3['total_score']) }}</div>
                                                    <div class="text-xs text-gray-400">{{ $item3['games_played'] }} game</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-5xl mb-3">🏆</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ autoTranslate('Belum ada pemain') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Leaderboard List Section -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="p-4 md:p-6">
                        <!-- Summary Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-500">{{ $games->total() }}</div>
                                <div class="text-xs text-gray-500">{{ autoTranslate('Total Pemain') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-500">{{ $games->sum('games_played') }}</div>
                                <div class="text-xs text-gray-500">{{ autoTranslate('Total Game Dimainkan') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-500">{{ number_format($games->isNotEmpty() ? $games->first()['total_score'] : 0) }}</div>
                                <div class="text-xs text-gray-500">{{ autoTranslate('Skor Tertinggi') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-500">{{ $gameNames->count() }}</div>
                                <div class="text-xs text-gray-500">{{ autoTranslate('Jenis Game') }}</div>
                            </div>
                        </div>
                        
                        <!-- Table Header -->
                        <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-3 mb-2 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="col-span-1 text-center">#</div>
                            <div class="col-span-5">{{ autoTranslate('Pemain') }}</div>
                            <div class="col-span-2">{{ autoTranslate('Game Dimainkan') }}</div>
                            <div class="col-span-2">{{ autoTranslate('Game') }}</div>
                            <div class="col-span-2 text-right">{{ autoTranslate('Total Skor') }}</div>
                        </div>
                        
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($games as $index => $item)
                                @php
                                    $rank = $games->firstItem() + $index;
                                    $user = $item['user'];
                                    $avatar = $user && $user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)) 
                                        ? asset('storage/' . $user->photo_profile) : null;
                                @endphp
                                <div class="py-3 md:py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 rounded-lg px-3">
                                    <div class="grid grid-cols-12 gap-3 md:gap-4 items-center">
                                        <!-- Rank -->
                                        <div class="col-span-2 md:col-span-1">
                                            @if($rank == 1)
                                                <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/50 flex items-center justify-center">
                                                    <span class="text-yellow-600 dark:text-yellow-400 font-bold text-sm">#1</span>
                                                </div>
                                            @elseif($rank == 2)
                                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                    <span class="text-gray-600 dark:text-gray-400 font-bold text-sm">#2</span>
                                                </div>
                                            @elseif($rank == 3)
                                                <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center">
                                                    <span class="text-orange-600 dark:text-orange-400 font-bold text-sm">#3</span>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">{{ $rank }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Player Info -->
                                        <div class="col-span-7 md:col-span-5 flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-md flex-shrink-0">
                                                @if($avatar)
                                                    <img src="{{ $avatar }}" alt="{{ $user->nama_mahasiswa ?? 'User' }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400 font-semibold text-base">
                                                        {{ strtoupper(substr($user->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-gray-800 dark:text-white text-sm md:text-base truncate">
                                                    {{ $user->nama_mahasiswa ?? ('User #' . ($item['id_user'] ?? '')) }}
                                                </div>
                                                <div class="text-xs text-gray-400 dark:text-gray-500 truncate md:hidden">
                                                    {{ $item['games_played'] }} game • {{ $item['game_name'] ?? 'Multiple' }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Games Played Count -->
                                        <div class="hidden md:block md:col-span-2">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item['games_played'] }}x</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Game Name (shows multiple if different games) -->
                                        <div class="hidden md:block md:col-span-2">
                                            @if(isset($item['game_name']))
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                                    {{ ucfirst($item['game_name']) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">{{ autoTranslate('Multiple Games') }}</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Total Score -->
                                        <div class="col-span-3 md:col-span-2 text-right">
                                            <div class="font-bold text-gray-800 dark:text-white text-base md:text-lg">
                                                {{ number_format($item['total_score']) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                                poin
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="text-6xl mb-4">🎮</div>
                                    <div class="text-gray-500 dark:text-gray-400 text-lg">{{ autoTranslate('Tidak ada hasil ditemukan') }}</div>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ autoTranslate('Belum ada data permainan') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($games->hasPages())
                    <div class="mt-6">
                        {{ $games->appends(request()->query())->links('vendor.pagination.custom_ajax', ['groupName' => 'leaderboard']) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .border-3 {
        border-width: 3px;
    }
    
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .dark ::-webkit-scrollbar-track {
        background: #1f2937;
    }
    
    .dark ::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
    
    .dark ::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
</style>
@endsection