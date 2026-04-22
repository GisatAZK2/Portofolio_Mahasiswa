<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';
    protected $primaryKey = 'id_games';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_postingan',
        'id_user',
        'game_name',
        'score',
        'playing_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Di app/Models/Game.php
public function getGameRoute()
{
    $routes = [
        'Matematika' => 'game.matematika',
        'matematika' => 'game.matematika',
        'Puzzle' => 'game.puzzle',
        'puzzle' => 'game.puzzle',
        'TTS' => 'game.tts',
        'tts' => 'game.tts',
        'Teka-Teki Silang' => 'game.tts',
    ];
    
    $routeName = $routes[$this->game_name] ?? $routes['Matematika'];
    
    return route($routeName, ['locale' => app()->getLocale()]);
}

    public function getDisplayName()
{
    $names = [
        'Matematika' => 'Matematika',
        'matematika' => 'Matematika',
        'Puzzle' => 'Puzzle',
        'puzzle' => 'Puzzle',
        'TTS' => 'Teka-Teki Silang',
        'tts' => 'Teka-Teki Silang',
        'Teka-Teki Silang' => 'Teka-Teki Silang',
    ];
    
    return $names[$this->game_name] ?? $this->game_name;
}
    public function postingan()
    {
        return $this->belongsTo(Postingan::class, 'id_postingan', 'id_postingan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
