<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Angkatan extends Model
{
    protected $table = 'angkatan';
    protected $primaryKey = 'id_angkatan';

    protected $fillable = [
        'nama_angkatan',
        'tahun_masuk',
        'tahun_keluar',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(User::class, 'id_angkatan');
    }
}
