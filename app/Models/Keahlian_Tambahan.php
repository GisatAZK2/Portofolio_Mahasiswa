<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Keahlian;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keahlian_Tambahan extends Model
{
    use HasFactory;
    protected $table = 'keahlian_tambahan';
    protected $primaryKey = 'id';

    protected $fillable = ['id_keahlian', 'id_user', 'is_active', 'status_pengajuan', 'keterangan'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function keahlian() {
        return $this->belongsTo(Keahlian::class, 'id_keahlian');
    }
}
