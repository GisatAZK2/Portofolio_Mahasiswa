<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class LikedPostingan extends Model
{
    use HasFactory;
    protected $table = 'liked_postingan';
    protected $primaryKey = 'id';   
    public $incrementing = true;                    
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_user',
        'id_postingan',
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