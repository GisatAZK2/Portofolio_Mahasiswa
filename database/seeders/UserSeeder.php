<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Indonesian locale
        $profilePhotoUrl = 'photo_profile/MWchDckJoc94l2X2SEJgp4FUWYXQ5bxf7lAentqY.jpg';

        // Get all available data
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        if ($jurusans->isEmpty() || $keahlians->isEmpty() || $angkatans->isEmpty()) {
            $this->command->error('Pastikan data Jurusan, Keahlian, dan Angkatan sudah ada sebelum menjalankan seeder ini.');
            return;
        }

        $this->command->info('Membuat 1000 user dummy...');

        $users = [];
        $batchSize = 100; // Insert in batches to avoid memory issues

        for ($i = 0; $i < 1000; $i++) {
            $nama = $faker->name;
            $username = 'user_' . ($i + 1) . '_' . strtolower(str_replace(' ', '_', $nama));
            $email = $username . '@example.com';

            // Ensure unique username and email
            while (User::where('username', $username)->exists()) {
                $username = 'user_' . ($i + 1) . '_' . $faker->unique()->word;
            }
            while (User::where('email', $email)->exists()) {
                $email = $username . '_' . $faker->unique()->numberBetween(1, 9999) . '@example.com';
            }

            $role = $faker->randomElement(['mahasiswa', 'dosen']);

            $users[] = [
                'nama_mahasiswa' => $nama,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password123'), // Default password
                'id_jurusan' => $jurusans->random()->id_jurusan,
                'id_keahlian' => $keahlians->random()->id_keahlian,
                'id_angkatan' => $angkatans->random()->id,
                'role' => $role,
                'photo_profile' => $profilePhotoUrl,
                'is_active' => true,
                'status_pengajuan' => 'Di Terima',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert in batches
            if (count($users) >= $batchSize) {
                User::insert($users);
                $users = [];
                $this->command->info("Inserted batch " . (($i + 1) / $batchSize) . " of 10");
            }
        }

        // Insert remaining users
        if (!empty($users)) {
            User::insert($users);
        }

        $this->command->info('Berhasil membuat 1000 user dummy!');
        $this->command->info('Password default: password123');
        $this->command->info('Photo profile: ' . $profilePhotoUrl);
    }
}