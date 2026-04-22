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
            'https://wallpapers.com/images/featured/cool-profile-picture-87h46gcobjl5e4xu.jpg',
            'https://englishleaflet.com/wp-content/uploads/2025/04/whatsapp-dp-80-e1744744670618.jpg',
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
            $this->command->error('Pastikan data Jurusan, Keahlian, dan Angkatan sudah ada sebelum menjalankan seeder ini.');
            return;
        }

        $this->command->info('Membuat 100 user dummy dengan background baru...');

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
            $jenis_kelamin = $faker->randomElement(['Laki-laki', 'Perempuan', 'Tidak ingin memberitahu']);

            $users[] = [
                'nama_mahasiswa'   => $nama,
                'username'         => $username,
                'email'            => $email,
                'password'         => Hash::make('password123'),
                'photo_profile'    => $faker->randomElement($photoProfiles),
                'background_url'   => $faker->randomElement($backgroundUrls),
                'jenis_kelamin'    => $jenis_kelamin,
                'status_pengajuan' => $faker->randomElement(['Sedang Di Ajukan', 'Di Terima', 'Di Tolak']),
                'deskripsi'        => $faker->paragraph(3),
                'keterangan'       => $faker->sentence(8),
                'id_jurusan'       => $jurusans->random()->id_jurusan,
                'id_keahlian'      => $keahlians->random()->id_keahlian,
                'id_angkatan'      => $angkatans->random()->id,
                'is_active'        => true,
                'role'             => $role,
                'video_url'        => $faker->randomElement($videoUrls),
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

        $this->command->info('✅ Berhasil membuat 100 user dummy!');
        $this->command->info('Password default     : password123');
        $this->command->info('Photo profile       : 2 link yang kamu tentukan (random)');
        $this->command->info('Background URL      : 4 link abstract yang baru ditambahkan (random)');
        $this->command->info('Video URL           : Contoh dari YouTube (random)');
    }
}