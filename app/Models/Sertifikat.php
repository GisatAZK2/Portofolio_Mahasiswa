<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class sertifikat extends Model
{
    protected $table = 'sertifikat';
    protected $primaryKey = 'id_sertifikat';

    protected $fillable = [
        'nama_sertifikat',
        'lembaga_penerbit',
        'tanggal_terbit',
        'link_sertifikat',
        'id_mahasiswa',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }
}
