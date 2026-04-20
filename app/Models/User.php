<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\LearningCorner;
use App\Models\Sertifikat;
use App\Models\Angkatan;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Keahlian_Tambahan;

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
    'keterangan',
    'id_keahlian',
    'id_angkatan',
    'deskripsi',
    'is_active',
    'status_pengajuan',
    'role',
    'video_url'
    ];
    
    protected $casts = [
    'keahlian_tambahan' => 'array',
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    ];

public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
}

public function postingans()
{
    return $this->hasMany(Postingan::class, 'id_user', 'id');
}

   public function keahlian()
{
    return $this->belongsTo(Keahlian::class, 'id_keahlian');
}

public function keahlianTambahan()
    {
        return $this->belongsToMany(
            Keahlian::class,
            'keahlian_tambahan',
            'id_user',
            'id_keahlian'
        )->withPivot('status_pengajuan', 'keterangan', 'is_active')
         ->withTimestamps();
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

public function leadingProjects()
{
    return $this->hasMany(Project::class, 'leader_id');
}

public function assignedTasks()
{
    return $this->hasMany(ProjectTask::class, 'user_id');
}

public function sertifikats()
{
    return $this->hasMany(Sertifikat::class, 'id_mahasiswa', 'id');
}

public function likedPostings()
{
    return $this->hasMany(LikedPostingan::class, 'id_user', 'id');
}

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
                Storage::disk('public')->delete($user->background_url);
            }

            $user->sertifikats()->get()->each->delete();
            $user->learning_corners()->get()->each->delete();
            $user->assignedTasks()->delete();
            $user->postingans()->delete();
            $user->likedPostings()->delete();
            $user->komentars()->delete();
            $user->keahlianTambahan()->detach();
            $user->memberProjects()->detach();

            $projects = $user->projects()->get()->merge($user->leadingProjects()->get())->unique('id');
            $projects->each->delete();
        });
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