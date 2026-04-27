<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GeneaLabs\LaravelModelCaching\Traits\Cachable; 
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class LearningCorner extends Model
{
    use HasFactory,Cachable;
    protected $table = 'learning_corner';
    protected $primaryKey = 'id_learning_corner';   
    public $incrementing = true;                    
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_mahasiswa',
        'project_id',
        'content',   
        'tanggal',
    ];

    protected $casts = [
        'content' => 'array',   
        'tanggal' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }

    public function canManage(?User $user): bool
    {
        // Jika user tidak login (guest), return false
        if (!$user) {
            return false;
        }
        
        // Load project jika belum di-load
        if (!$this->relationLoaded('project')) {
            $this->load('project');
        }
        
        // Cek apakah user adalah owner project atau leader
        if ($this->project && ($this->project->id_mahasiswa === $user->id || 
            $this->project->leader_id === $user->id)) {
            return true;
        }

        // Cek apakah user adalah pembuat entri
        return $this->id_mahasiswa === $user->id;
    }

    public function canManageDosen(?User $user): bool
    {
        if (!$user || $user->role !== 'dosen') {
            return false;
        }

        if (!$this->relationLoaded('mahasiswa')) {
            $this->load('mahasiswa');
        }

        if (!$this->mahasiswa) {
            return false;
        }

        return $user->id_angkatan === $this->mahasiswa->id_angkatan
            && $user->id_jurusan === $this->mahasiswa->id_jurusan
            && $user->id_keahlian === $this->mahasiswa->id_keahlian;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    protected static function booted(): void
    {
        static::deleting(function (LearningCorner $learningCorner) {
            foreach ($learningCorner->content ?? [] as $item) {
                if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                    Storage::disk('public')->delete($item['content']);
                }
            }
        });
    }

    public function getJudulAttribute()
    {
        foreach ($this->content ?? [] as $item) {
            if (($item['type'] ?? '') === 'title') {
                return $item['content'] ?? '(tanpa judul)';
            }
        }
        return '(tanpa judul)';
    }
}