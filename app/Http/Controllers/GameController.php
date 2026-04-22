<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postingan;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display the math game view.
     */
    public function mtk(Request $request)
    {
        // optional: accept postingan id and game id to track/initialize game session
        $postingan = null;
        $game = null;

        if ($request->query('postingan')) {
            $postingan = Postingan::where('id_postingan', $request->query('postingan'))->first();
        }

        if ($request->query('game')) {
            $game = Game::where('id_games', $request->query('game'))->first();
        } elseif ($postingan) {
            $game = $postingan->game ?? null;
        }

        return view('games.views_game_mtk', compact('postingan', 'game'));
    }

    public function puzzle(Request $request)
{
    // optional: accept postingan id and game id to track/initialize game session
    $postingan = null;
    $game = null;

    if ($request->query('postingan')) {
        $postingan = Postingan::where('id_postingan', $request->query('postingan'))->first();
    }

    if ($request->query('game')) {
        $game = Game::where('id_games', $request->query('game'))->first();
    } elseif ($postingan) {
        $game = $postingan->game ?? null;
    }

    return view('games.views_game_puzzle', compact('postingan', 'game'));
}

/**
 * Display the TTS (Crossword) game view.
 */
public function tts(Request $request)
{
    // optional: accept postingan id and game id to track/initialize game session
    $postingan = null;
    $game = null;

    if ($request->query('postingan')) {
        $postingan = Postingan::where('id_postingan', $request->query('postingan'))->first();
    }

    if ($request->query('game')) {
        $game = Game::where('id_games', $request->query('game'))->first();
    } elseif ($postingan) {
        $game = $postingan->game ?? null;
    }

    return view('games.views_game_tts', compact('postingan', 'game'));
}

 /**
 * Get leaderboard data with grouped scores by user and game
 */
public function leaderboard(Request $request)
{
    $gameFilter = $request->query('game');
    
    // Get all unique game names for filter
    $gameNames = Game::select('game_name')->distinct()->pluck('game_name');
    
    // Build query with relationships
    $query = Game::with(['user', 'postingan']);
    
    if ($gameFilter) {
        $query->where('game_name', $gameFilter);
    }
    
    // Get all games first
    $allGames = $query->get();
    
    // Group scores by user_id and game_name, sum the scores
    $groupedScores = [];
    
    foreach ($allGames as $game) {
        $key = $game->id_user . '_' . $game->game_name;
        
        if (!isset($groupedScores[$key])) {
            $groupedScores[$key] = [
                'id_user' => $game->id_user,
                'user' => $game->user,
                'game_name' => $game->game_name,
                'total_score' => 0,
                'total_playing_time' => 0,
                'games_played' => 0,
                'postingan' => $game->postingan,
                'latest_updated_at' => $game->updated_at
            ];
        }
        
        $groupedScores[$key]['total_score'] += (int)$game->score;
        $groupedScores[$key]['total_playing_time'] += $this->convertPlayingTimeToSeconds($game->playing_time);
        $groupedScores[$key]['games_played']++;
        
        // Keep latest update
        if ($game->updated_at > $groupedScores[$key]['latest_updated_at']) {
            $groupedScores[$key]['latest_updated_at'] = $game->updated_at;
        }
    }
    
    // Convert to collection and sort by total_score descending
    $collection = collect($groupedScores)->values();
    
    // Sort by total_score DESC
    $sorted = $collection->sortByDesc(function ($item) {
        return $item['total_score'];
    });
    
    // Add rank to each item
    $rankedScores = $sorted->values()->map(function ($item, $index) {
        $item['rank'] = $index + 1;
        $item['formatted_playing_time'] = $this->formatSecondsToTime($item['total_playing_time']);
        return $item;
    });
    
    // Paginate manually (15 per page)
    $perPage = 15;
    $currentPage = request()->get('page', 1);
    $currentItems = $rankedScores->slice(($currentPage - 1) * $perPage, $perPage);
    
    $games = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $rankedScores->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    return view('games.leaderboard', compact('games', 'gameNames', 'gameFilter'));
}

/**
 * Get player statistics for dashboard
 */
public function getPlayerStats(Request $request)
{
    $userId = Auth::id();
    
    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ]);
    }
    
    $gameFilter = $request->query('game');
    
    $query = Game::where('id_user', $userId);
    
    if ($gameFilter) {
        $query->where('game_name', $gameFilter);
    }
    
    $userGames = $query->get();
    
    // Group by game_name
    $groupedByGame = [];
    $totalScore = 0;
    $totalGamesPlayed = 0;
    
    foreach ($userGames as $game) {
        $gameName = $game->game_name;
        
        if (!isset($groupedByGame[$gameName])) {
            $groupedByGame[$gameName] = [
                'game_name' => $gameName,
                'total_score' => 0,
                'games_played' => 0,
                'best_score' => 0,
                'playing_time' => 0
            ];
        }
        
        $groupedByGame[$gameName]['total_score'] += (int)$game->score;
        $groupedByGame[$gameName]['games_played']++;
        $groupedByGame[$gameName]['best_score'] = max($groupedByGame[$gameName]['best_score'], (int)$game->score);
        $groupedByGame[$gameName]['playing_time'] += $this->convertPlayingTimeToSeconds($game->playing_time);
        
        $totalScore += (int)$game->score;
        $totalGamesPlayed++;
    }
    
    return response()->json([
        'success' => true,
        'data' => [
            'total_score' => $totalScore,
            'total_games_played' => $totalGamesPlayed,
            'games_played' => $totalGamesPlayed,
            'by_game' => array_values($groupedByGame),
            'rank' => $this->getUserRank($userId, $gameFilter)
        ]
    ]);
}

/**
 * Get user's rank
 */
private function getUserRank($userId, $gameFilter = null)
{
    $query = Game::with('user');
    
    if ($gameFilter) {
        $query->where('game_name', $gameFilter);
    }
    
    $allGames = $query->get();
    
    // Group and sum scores
    $groupedScores = [];
    
    foreach ($allGames as $game) {
        $key = $game->id_user . '_' . $game->game_name;
        
        if (!isset($groupedScores[$key])) {
            $groupedScores[$key] = [
                'id_user' => $game->id_user,
                'total_score' => 0
            ];
        }
        
        $groupedScores[$key]['total_score'] += (int)$game->score;
    }
    
    // Sort by total_score
    usort($groupedScores, function ($a, $b) {
        return $b['total_score'] - $a['total_score'];
    });
    
    // Find user's rank
    foreach ($groupedScores as $index => $score) {
        if ($score['id_user'] == $userId) {
            return $index + 1;
        }
    }
    
    return null;
}

/**
 * Convert playing time string to seconds
 */
private function convertPlayingTimeToSeconds($timeString)
{
    if (empty($timeString)) return 0;
    
    // Handle format like "45s", "1m30s", "2m"
    $seconds = 0;
    
    // Extract minutes
    if (preg_match('/(\d+)m/', $timeString, $matches)) {
        $seconds += (int)$matches[1] * 60;
    }
    
    // Extract seconds
    if (preg_match('/(\d+)s/', $timeString, $matches)) {
        $seconds += (int)$matches[1];
    }
    
    // If just a number, treat as seconds
    if (is_numeric($timeString)) {
        $seconds = (int)$timeString;
    }
    
    return $seconds;
}

/**
 * Format seconds back to readable time
 */
private function formatSecondsToTime($seconds)
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    
    if ($minutes > 0) {
        return $minutes . 'm ' . $remainingSeconds . 's';
    }
    
    return $remainingSeconds . 's';
}

   /**
 * Save or update a game's score and playing time (only if higher than existing)
 */
public function saveScore(Request $request)
{
    $validated = $request->validate([
        'id_games' => 'nullable|integer|exists:games,id_games',
        'id_postingan' => 'nullable|integer|exists:postingan,id_postingan',
        'score' => 'required|integer|min:0',
        'playing_time' => 'nullable|string|max:50',
    ]);

    $userId = Auth::id();
    $newScore = (int)$validated['score'];

    // Try to find existing game record
    $game = null;
    if (!empty($validated['id_games'])) {
        $game = Game::where('id_games', $validated['id_games'])->where('id_user', $userId)->first();
    }

    if (!$game && !empty($validated['id_postingan'])) {
        $game = Game::where('id_postingan', $validated['id_postingan'])->where('id_user', $userId)->first();
    }

    if ($game) {
        // Only update if the new score is higher than the existing score
        $currentScore = (int)$game->score;
        if ($newScore > $currentScore) {
            $game->score = (string)$newScore;
            $game->playing_time = $validated['playing_time'] ?? $game->playing_time;
            $game->save();
            
            return response()->json([
                'success' => true, 
                'id_games' => $game->id_games,
                'is_new_record' => true,
                'previous_score' => $currentScore,
                'new_score' => $newScore
            ]);
        } else {
            // Score is not higher, don't save
            return response()->json([
                'success' => false, 
                'id_games' => $game->id_games,
                'is_new_record' => false,
                'message' => 'Score not higher than existing record',
                'current_best' => $currentScore
            ]);
        }
    } else {
        // Create new game record (first time playing)
        $game = Game::create([
            'id_postingan' => $validated['id_postingan'] ?? null,
            'id_user' => $userId,
            'game_name' => 'Matematika',
            'score' => (string)$newScore,
            'playing_time' => $validated['playing_time'] ?? '0',
            'is_active' => true,
        ]);
        
        return response()->json([
            'success' => true, 
            'id_games' => $game->id_games,
            'is_new_record' => true,
            'message' => 'First time score saved'
        ]);
    }
}
}
