<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project_User;
use App\Models\ProjectTask;

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
        'viewer_ids' => 'array', 
    ];

    // Relasi ke User
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }

    public function scopeCanBeEditedBy($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('id_mahasiswa', $userId)
              ->orWhere('leader_id', $userId);
        });
    }

    public function recordView(int $userId): bool
    {
    $viewers = $this->viewer_ids ?? [];
    
    if (!in_array($userId, $viewers)) {
        $viewers[] = $userId;
        $this->viewer_ids = $viewers;
        $this->save();
        return true;
    }
    
    return false;
}

    /**
     * Hitung jumlah views unik
     *
     * @return int
     */
    public function getUniqueViewsCountAttribute(): int
    {
        return count($this->viewer_ids ?? []);
    }

    /**
     * Cek apakah user tertentu sudah pernah melihat
     *
     * @param int $userId
     * @return bool
     */
    public function hasBeenViewedBy(int $userId): bool
    {
        return in_array($userId, $this->viewer_ids ?? []);
    }


    // Helper: apakah user ini bisa edit/hapus project ini
    public function canBeEditedByUser(?User $user): bool
    {
        if (!$user) return false;
        return $this->id_mahasiswa === $user->id || $this->leader_id === $user->id;
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members(){
    return $this->belongsToMany(
        User::class,
        'project_user',
        'project_id',
        'user_id'
    );
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class, 'project_id', 'id');
    }

    public function learningCorners() {
        return $this->hasMany(LearningCorner::class, 'project_id', 'id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            $project->learningCorners()->get()->each->delete();
            $project->tasks()->delete();
            $project->members()->detach();
        });
    }
}