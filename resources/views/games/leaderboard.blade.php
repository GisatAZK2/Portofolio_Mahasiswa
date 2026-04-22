@extends('Layout.Layout')

@section('title', autoTranslate('Leaderboard'))

@section('content')
    <div class="min-h-screen py-8 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header / Title -->
            <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-400 dark:from-yellow-400 dark:to-yellow-300 bg-clip-text text-transparent">
                        {{ autoTranslate('Games Leaderboard') }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        {{ autoTranslate('Top players by score. Use the filter to view a specific game.') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}" id="filterForm">
                        <label for="game_filter" class="sr-only">{{ autoTranslate('Filter Game') }}</label>
                        <select id="game_filter" name="game" onchange="this.form.submit()" 
                            class="px-4 py-2 border rounded-xl bg-white dark:bg-gray-800 text-sm border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-400 focus:border-transparent cursor-pointer shadow-sm">
                            <option value="">{{ autoTranslate('All Games') }}</option>
                            @foreach($gameNames as $name)
                                <option value="{{ $name }}" {{ (isset($gameFilter) && $gameFilter === $name) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Leaderboard Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
                <div class="p-4 md:p-6">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($games as $index => $g)
                            @php
                                $rank = $games->firstItem() + $index;
                                $user = $g->user;
                                $avatar = $user && $user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)) ? asset('storage/' . $user->photo_profile) : null;
                                
                                // Medal styling for top 3
                                $medalClass = '';
                                $medalIcon = '';
                                $rankBgClass = '';
                                if ($rank == 1) {
                                    $medalClass = 'text-yellow-500';
                                    $medalIcon = '🥇';
                                    $rankBgClass = 'bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20';
                                } elseif ($rank == 2) {
                                    $medalClass = 'text-gray-400';
                                    $medalIcon = '🥈';
                                    $rankBgClass = 'bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50';
                                } elseif ($rank == 3) {
                                    $medalClass = 'text-amber-700';
                                    $medalIcon = '🥉';
                                    $rankBgClass = 'bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20';
                                }
                            @endphp
                            <li class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 rounded-lg px-3 {{ $rankBgClass }}">
                                <div class="flex items-center gap-4">
                                    <!-- Rank with Medal -->
                                    <div class="w-12 text-center">
                                        @if($rank <= 3)
                                            <div class="text-2xl {{ $medalClass }}" title="{{ $medalIcon }} {{ ['1st','2nd','3rd'][$rank-1] }} Place">
                                                {{ $medalIcon }}
                                            </div>
                                            <div class="text-xs font-semibold {{ $medalClass }} mt-1">
                                                #{{ $rank }}
                                            </div>
                                        @else
                                            <div class="text-sm font-bold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 w-8 h-8 rounded-full flex items-center justify-center mx-auto">
                                                {{ $rank }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-md ring-2 {{ $rank == 1 ? 'ring-yellow-400' : ($rank == 2 ? 'ring-gray-300' : ($rank == 3 ? 'ring-amber-600' : 'ring-gray-200 dark:ring-gray-600')) }}">
                                        @if($avatar)
                                            <img src="{{ $avatar }}" alt="{{ $user->nama_mahasiswa ?? 'User' }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-500 font-semibold text-lg">{{ strtoupper(substr($user->nama_mahasiswa ?? 'U',0,1)) }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- User Info -->
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white {{ $rank == 1 ? 'text-lg' : 'text-base' }}">
                                            {{ $user->nama_mahasiswa ?? ('User #' . ($g->id_user ?? '')) }}
                                            @if($rank == 1)
                                                <span class="ml-1 text-xs bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 px-2 py-0.5 rounded-full">👑</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                            {{ optional($g->postingan)->judul ?? (optional($g->postingan)->id_postingan ? autoTranslate('Postingan') . ' #' . optional($g->postingan)->id_postingan : autoTranslate('General')) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Score and Time -->
                                <div class="text-right ml-14 sm:ml-0">
                                    <div class="font-bold text-2xl {{ $rank == 1 ? 'text-yellow-600 dark:text-yellow-400' : ($rank == 2 ? 'text-gray-500 dark:text-gray-300' : ($rank == 3 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-800 dark:text-gray-200')) }}">
                                        {{ number_format($g->score) }}
                                        @if($rank == 1)
                                            <span class="text-sm">🏆</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-end gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $g->playing_time }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-12 text-center">
                                <div class="text-6xl mb-4">🎮</div>
                                <div class="text-gray-500 dark:text-gray-400 text-lg">{{ autoTranslate('No results found') }}</div>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ autoTranslate('Try changing the game filter or check back later') }}</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($games, 'links') && $games->hasPages())
                <div class="mt-8" data-pagination-group="leaderboard">
                    {{ $games->appends(request()->query())->links('vendor.pagination.custom_ajax', ['groupName' => 'leaderboard']) }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom animation for top ranks */
        @keyframes subtleGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(234, 179, 8, 0.2); }
            50% { box-shadow: 0 0 15px rgba(234, 179, 8, 0.4); }
        }
        
        .bg-gradient-to-r.from-yellow-50 {
            animation: subtleGlow 2s ease-in-out infinite;
        }
    </style>
@endsection