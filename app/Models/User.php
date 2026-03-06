<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\LearningCorner;
use App\Models\Sertifikat;
use App\Models\Angkatan;
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
    'email',
    'photo_profile',
    'username',
    'password',
    'jenis_kelamin',
    'background_url',
    'id_jurusan',
    'keahlian_tambahan',
    'id_keahlian',
    'id_angkatan',
    'deskripsi',
    'is_active',
    'role'
    ];
    
    protected $casts = [
    'keahlian_tambahan' => 'array',
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    ];

    public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan');
}

   public function keahlian()
{
    return $this->belongsTo(Keahlian::class, 'id_keahlian');
}

public function angkatan() {
    return $this->belongsTo(Angkatan::class, 'id_angkatan', 'id');
}


public function learning_corners()
{
    return $this->hasMany(LearningCorner::class, 'id_mahasiswa');
}

public function projects()
{
    return $this->hasMany(Project::class, 'id_mahasiswa', 'id');
}

public function sertifikats()
{
    return $this->hasMany(Sertifikat::class, 'id_mahasiswa', 'id');
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
}

