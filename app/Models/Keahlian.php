<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $table = 'keahlian';
    protected $primaryKey = 'id_keahlian';
    protected $fillable = ['nama_keahlian'];

    
}
