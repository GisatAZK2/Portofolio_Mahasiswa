<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Keahlian;

class KeahlianSeeder extends Seeder
{
    
public function run(): void
{
    Keahlian::insert([
        ['nama_keahlian' => 'Web Development'],
        ['nama_keahlian' => 'Mobile Development'],
        ['nama_keahlian' => 'UI/UX Design'],
        ['nama_keahlian' => 'Data Analyst'],
    ]);
}
}
