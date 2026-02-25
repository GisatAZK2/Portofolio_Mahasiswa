<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Angkatan;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Angkatan::create([
            'nama_angkatan' => 'Angkatan 2026',
            'tahun_masuk' => '2026-07-13',
            'tahun_keluar' => '2026-07-13',
        ]);

        Angkatan::create([
            'nama_angkatan' => 'Angkatan 2027',
            'tahun_masuk' => '2026-07-13',
            'tahun_keluar' => '2027-07-13',
        ]);

        Angkatan::create([
            'nama_angkatan' => 'Angkatan 2028',
            'tahun_masuk' => '2027-07-13',
            'tahun_keluar' => '2028-07-13',
        ]);

    }
}
