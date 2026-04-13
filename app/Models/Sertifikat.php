<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';

    protected $fillable = [
        'nama_sertifikat',
        'lembaga_penerbit',
        'tanggal_terbit',
        'link_sertifikat',
        'id_mahasiswa',
        'status_pengajuan',
        'keterangan',
        'is_active'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Sertifikat $sertifikat) {
            if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
                Storage::disk('public')->delete($sertifikat->link_sertifikat);
            }
        });
    }
}