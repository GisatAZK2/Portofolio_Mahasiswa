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
        $faker = Faker::create('id_ID');

        $photoProfiles = [
            'photos/01fc73b1-9b0b-46ef-8af7-6dadb337ac11.webp',
            'photos/c0a28dd7-4427-4ca8-982d-6aec690e779a.webp',
        ];

        $backgroundUrls = [
            'https://img.freepik.com/free-vector/abstract-colorful-technology-dotted-wave-background_1035-17450.jpg',
            'https://marketplace.canva.com/EAGQ4hBhXII/1/0/1600w/canva-blue-abstract-desktop-wallpaper-htGu4av79Lo.jpg',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSX0SGKvMmF-7SHP-G8BNd5rvG4zYqwn-R26g&s',
            'https://www.shutterstock.com/image-vector/abstract-technology-background-motion-neon-600nw-2744966321.jpg',
            null,
        ];

        $videoUrls = [
            'https://www.youtube.com/watch?v=V9Kg2y2585E',
            'https://www.youtube.com/watch?v=LOS5WB75gkY',
            'https://www.youtube.com/shorts/8x7-G9SALJk',
            'https://www.youtube.com/watch?v=w4GSylZAQzo',
            'https://www.youtube.com/watch?v=BVMTOwyD3CU',
            null,
        ];

        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        if ($jurusans->isEmpty() || $keahlians->isEmpty() || $angkatans->isEmpty()) {
            $this->command->error('Pastikan data Jurusan, Keahlian, dan Angkatan sudah ada.');
            return;
        }

        $this->command->info('Membuat 100 user dummy...');

        $users = [];
        $batchSize = 100;

        for ($i = 0; $i < 100; $i++) {
            $nama = $faker->name;

            $baseUsername = 'user_' . ($i + 1) . '_' . strtolower(str_replace([' ', '.'], '_', $nama));
            $username = $baseUsername;
            $email = $username . '@example.com';

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $faker->unique()->numberBetween(1, 999);
            }

            while (User::where('email', $email)->exists()) {
                $email = $username . '_' . $faker->unique()->numberBetween(1, 9999) . '@example.com';
            }

            $role = $faker->randomElement(['mahasiswa', 'dosen', 'admin']);

            // NIM hanya untuk mahasiswa
            $nim = null;
            if ($role === 'mahasiswa') {
                $nim = $faker->unique()->numerify('##########'); // 10 digit angka
            }

            // tanggal lahir tidak lebih dari hari ini
            $tanggalLahir = $faker->dateTimeBetween('-30 years', 'today')->format('Y-m-d');

            $users[] = [
                'nama_mahasiswa'   => $nama,
                'username'         => $username,
                'email'            => $email,
                'password'         => Hash::make('password123'),
                'photo_profile'    => $faker->randomElement($photoProfiles),
                'background_url'   => $faker->randomElement($backgroundUrls),
                'status_pengajuan' => $faker->randomElement(['Sedang Di Ajukan', 'Di Terima', 'Di Tolak']),
                'deskripsi'        => $faker->paragraph(3),
                'keterangan'       => $faker->sentence(8),
                'id_jurusan'       => $jurusans->random()->id_jurusan,
                'id_keahlian'      => $keahlians->random()->id_keahlian,
                'id_angkatan'      => $angkatans->random()->id,
                'is_active'        => true,
                'role'             => $role,
                'video_url'        => $faker->randomElement($videoUrls),
                'nim'              => $nim,
                'tanggal_lahir'    => $tanggalLahir,
                'remember_token'   => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            if (count($users) >= $batchSize) {
                User::insert($users);
                $users = [];
                $this->command->info("Inserted batch " . (int)(($i + 1) / $batchSize));
            }
        }

        if (!empty($users)) {
            User::insert($users);
        }

        $this->command->info(' Berhasil membuat 100 user dummy!');
        $this->command->info('Password default : password123');

        // ─── Akun Admin ───────────────────────────────────────────────────────
        $this->command->info('Membuat akun admin...');

        User::withoutEvents(function () use ($jurusans, $keahlians, $angkatans, $photoProfiles, $backgroundUrls) {
            User::updateOrCreate(
                ['email' => 'admin@portofolio.com'],
                [
                    'nama_mahasiswa'   => 'Administrator',
                    'username'         => 'admin',
                    'slug'             => 'administrator',
                    'email'            => 'admin@portofolio.com',
                    'password'         => Hash::make('admin123'),
                    'photo_profile'    => $photoProfiles[0],
                    'background_url'   => $backgroundUrls[0],
                    'status_pengajuan' => 'Di Terima',
                    'deskripsi'        => 'Akun administrator sistem portofolio mahasiswa.',
                    'keterangan'       => 'Admin utama sistem.',
                    'id_jurusan'       => $jurusans->first()->id_jurusan,
                    'id_keahlian'      => $keahlians->first()->id_keahlian,
                    'id_angkatan'      => $angkatans->first()->id,
                    'is_active'        => true,
                    'role'             => 'admin',
                    'video_url'        => null,
                    'nim'              => null,
                    'tanggal_lahir'    => '1990-01-01',
                    'remember_token'   => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            );
        });

        $this->command->info('  Akun admin berhasil dibuat!');
        $this->command->info('   Email    : admin@portofolio.com');
        $this->command->info('   Password : admin123');

        // ─── Akun Dosen ───────────────────────────────────────────────────────
        $this->command->info('Membuat 1 akun dosen...');

        User::withoutEvents(function () use ($jurusans, $keahlians, $angkatans, $photoProfiles, $backgroundUrls) {
            User::updateOrCreate(
                ['email' => 'budi.santoso@portofolio.com'],
                [
                    'nama_mahasiswa'   => 'Dr. Budi Santoso, M.Kom',
                    'username'         => 'dosen_budi',
                    'slug'             => 'dr-budi-santoso-mkom',
                    'email'            => 'budi.santoso@portofolio.com',
                    'password'         => Hash::make('dosen123'),
                    'photo_profile'    => $photoProfiles[1],
                    'background_url'   => $backgroundUrls[1],
                    'status_pengajuan' => 'Di Terima',
                    'deskripsi'        => 'Dosen pengampu mata kuliah Pemrograman Web dan Mobile.',
                    'keterangan'       => 'Dosen Tetap Prodi Teknik Informatika.',
                    'id_jurusan'       => $jurusans->first()->id_jurusan,
                    'id_keahlian'      => $keahlians->first()->id_keahlian,
                    'id_angkatan'      => $angkatans->first()->id,
                    'is_active'        => true,
                    'role'             => 'dosen',
                    'video_url'        => null,
                    'nim'              => null,
                    'tanggal_lahir'    => '1985-03-15',
                    'remember_token'   => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            );
        });

        $this->command->info(' Akun dosen berhasil dibuat!');
        $this->command->info('   Email    : budi.santoso@portofolio.com');
        $this->command->info('   Password : dosen123');
    }
}