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
     * Show leaderboard for games
     */
    public function leaderboard(Request $request)
    {
        // allow filtering by game name
        $gameFilter = $request->query('game');

        // list of available games for filter
        $gameNames = Game::select('game_name')->distinct()->pluck('game_name');

        $query = Game::with(['user', 'postingan']);
        if ($gameFilter) {
            $query->where('game_name', $gameFilter);
        }

        // order by numeric score desc and paginate (15 items per page)
        $games = $query->orderByRaw('CAST(score AS UNSIGNED) DESC')->paginate(15);

        return view('games.leaderboard', compact('games', 'gameNames', 'gameFilter'));
    }

    /**
 * Get the player's highest score for a specific game/postingan
 */
public function getHighestScore(Request $request)
{
    $validated = $request->validate([
        'id_postingan' => 'nullable|integer|exists:postingan,id_postingan',
    ]);

    $userId = Auth::id();
    
    $query = Game::where('id_user', $userId);
    
    if (!empty($validated['id_postingan'])) {
        $query->where('id_postingan', $validated['id_postingan']);
    }
    
    $highestScore = $query->orderByRaw('CAST(score AS UNSIGNED) DESC')->first();
    
    return response()->json([
        'highest_score' => $highestScore ? (int)$highestScore->score : 0,
        'game_id' => $highestScore ? $highestScore->id_games : null
    ]);
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
