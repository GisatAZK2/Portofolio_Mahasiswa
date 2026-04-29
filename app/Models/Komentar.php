<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GeneaLabs\LaravelModelCaching\Traits\Cachable; 
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Postingan;

class Komentar extends Model
{
    use HasFactory,Cachable;
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';   
    public $incrementing = true;                    
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_user',
        'id_postingan',
        'komentar',   
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function postingan()
    {
        return $this->belongsTo(Postingan::class, 'id_postingan', 'id_postingan');
    }

}