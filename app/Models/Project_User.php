<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Project;

class Project_User extends Model
{
    use HasFactory;
    protected $table = 'project_user';

    protected $fillable = ['project_id','user_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}



