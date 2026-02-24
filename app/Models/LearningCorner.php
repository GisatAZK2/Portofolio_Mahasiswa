<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningCorner extends Model
{
    protected $primaryKey = 'id_learning_corner';

    protected $table = 'learning_corner';
    protected $fillable = [
        "id_mahasiswa",
        "isi_learning_corner",
        "tanggal"
    ];
}
