<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GeneaLabs\LaravelModelCaching\Traits\Cachable; 
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Game;
use App\Traits\HasTranslations;

class Postingan extends Model
{
    use HasFactory, Cachable, HasTranslations;
    protected $table = 'postingan';
    protected $primaryKey = 'id_postingan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'content',
        'tanggal',
        'translations',
    ];

    protected $casts = [
        'content' => 'array',
        'tanggal' => 'datetime',
        'translations' => 'array',
    ];

    protected array $translatableFields = ['content'];
    protected array $translatableItemTypes = ['title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_postingan', 'id_postingan');
    }

    public function likes()
    {
        return $this->hasMany(LikedPostingan::class, 'id_postingan', 'id_postingan');
    }

    public function game()
    {
        return $this->hasOne(Game::class, 'id_postingan', 'id_postingan');
    }

    protected static function booted(): void
    {
        static::deleting(function (Postingan $postingan) {
            foreach ($postingan->content ?? [] as $item) {
                if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                    Storage::disk('public')->delete($item['content']);
                }
            }
        });
    }

}