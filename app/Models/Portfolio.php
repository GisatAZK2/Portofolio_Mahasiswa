<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';
    protected $primaryKey = 'id_portfolio';
    public $timestamps = false;

    protected $fillable = [
        'isi_content',
        'id_mahasiswa',
        'tanggal'
    ];

    protected $casts = [
        'isi_content' => 'array'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }
}