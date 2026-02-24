<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Portofolio;
use App\Models\LearningCorner;
use App\Models\Project;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
    'nama_mahasiswa',
    'photo_profile',
    'username',
    'password',
    'id_jurusan',
    'id_keahlian',
    'is_active'
    ];

    public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan');
}

   public function keahlian()
{
    return $this->belongsTo(Keahlian::class, 'id_keahlian');
}

public function portofolio()
{
    return $this->hasMany(Portofolio::class, 'id_mahasiswa');
}

public function learning_corners()
{
    return $this->hasMany(LearningCorner::class, 'id_mahasiswa');
}

public function projects()
{
    return $this->hasMany(Project::class, 'id_mahasiswa', 'id');
}



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

