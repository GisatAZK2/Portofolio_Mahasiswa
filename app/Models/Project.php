<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    public $timestamps = false; // karena tidak pakai created_at

    protected $fillable = [
        'nama_project',
        'tanggal_mulai',
        'tanggal_akhir',
        'link_project',
        'id_mahasiswa'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
    ];

    // Relasi ke User
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }
}