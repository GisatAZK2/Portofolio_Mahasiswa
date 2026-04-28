<?php

namespace Database\Seeders;

use App\Models\LearningCorner;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AngkatanSeeder::class,
            JurusanSeeder::class,
            KeahlianSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            LearningCornerSeeder::class,
            SertifikatSeeder::class,
        ]);
    }
}