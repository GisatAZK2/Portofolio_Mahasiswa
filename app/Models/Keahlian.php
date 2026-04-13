<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $table = 'keahlian';
    protected $primaryKey = 'id_keahlian';
    protected $fillable = ['nama_keahlian'];

      public function users()
    {
        return $this->hasMany(User::class, 'id_keahlian');
    }
    
    protected static function booted(): void
    {
        static::deleting(function (Keahlian $keahlian) {
            $keahlian->users()->get()->each->delete();
        });
    }
}
