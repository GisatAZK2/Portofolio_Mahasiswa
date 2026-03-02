<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    

    protected $fillable = [
        'isi_content',
        'tanggal_mulai',
        'tanggal_akhir',
        'id_mahasiswa',
        'leader_id'
    ];

    protected $casts = [
        'isi_content' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
    ];

    // Relasi ke User
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }
}