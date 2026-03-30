<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;


class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
public function run(): void
{
    Jurusan::insert([
        ['nama_jurusan' => 'Teknologi Rekayasa Manufaktur'],
        ['nama_jurusan' => 'Bisnis Digital'],
        ['nama_jurusan' => 'Teknologi Rekayasa Perangkat Lunak'],
    ]);
}
}

