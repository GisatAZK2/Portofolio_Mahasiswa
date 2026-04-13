<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Angkatan extends Model
{
    protected $table = 'angkatan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_angkatan',
        'tahun_masuk',
        'tahun_keluar',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(User::class, 'id_angkatan', 'id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Angkatan $angkatan) {
            $angkatan->mahasiswa()->get()->each->delete();
        });
    }
}
