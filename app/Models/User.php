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
    'status_pengajuan',
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
    return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
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

public function memberProjects()
{
    return $this->belongsToMany(
        Project::class,
        'project_user',
        'user_id',
        'project_id'
    );
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function getJenisKelaminFormattedAttribute()
    {
        if ($this->jenis_kelamin == 'L' || $this->jenis_kelamin == 'Laki-laki') {
            return 'Laki-laki';
        } elseif ($this->jenis_kelamin == 'P' || $this->jenis_kelamin == 'Perempuan') {
            return 'Perempuan';
        }
        return $this->jenis_kelamin;
    }

    // Mutator untuk jenis kelamin (menyeragamkan penyimpanan)
    public function setJenisKelaminAttribute($value)
    {
        if ($value == 'L' || $value == 'Laki-laki') {
            $this->attributes['jenis_kelamin'] = 'Laki-laki';
        } elseif ($value == 'P' || $value == 'Perempuan') {
            $this->attributes['jenis_kelamin'] = 'Perempuan';
        } else {
            $this->attributes['jenis_kelamin'] = $value;
        }
    }
}