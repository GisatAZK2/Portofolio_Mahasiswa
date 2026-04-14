<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Postingan extends Model
{
    use HasFactory;
    protected $table = 'postingan';
    protected $primaryKey = 'id_postingan';   
    public $incrementing = true;                    
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_user',
        'content',   
        'tanggal',
    ];

    protected $casts = [
        'content' => 'array',   
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}