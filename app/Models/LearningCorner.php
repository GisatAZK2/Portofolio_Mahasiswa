<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class LearningCorner extends Model
{
    use HasFactory;
    protected $table = 'learning_corner';
    protected $primaryKey = 'id_learning_corner';   
    public $incrementing = true;                    
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_mahasiswa',
        'project_id',  // json
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

public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
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
