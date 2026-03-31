<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sertifikat;
use App\Models\User;
use Faker\Factory as Faker;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = User::where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')->get();
        if ($users->isEmpty()) {
            $this->command->error('Tidak ada user mahasiswa. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $certificateNames = [
            'Front-End Web Development',
            'Back-End Development',
            'Full-Stack Web Development',
            'Responsive Web Design',
            'JavaScript Algorithms and Data Structures',
            'Laravel for Beginners',
            'PHP Web Development',
            'Modern CSS Layouts',
            'API Development with Laravel',
            'Database Design Fundamentals'
        ];

        $issuers = [
            'Coursera',
            'Udemy',
            'edX',
            'freeCodeCamp',
            'Codecademy',
            'Microsoft Learn',
            'Google Digital Garage',
            'LinkedIn Learning',
            'Khan Academy',
            'Pluralsight'
        ];
        
       $links = [
        'photos/Biru Minimalis Pesan Obrolan Logo (1).png',
        // Tambahkan beberapa link lain kalau mau lebih variatif
        // 'photos/sertifikat-default.jpg',
        ];
        $statuses = ['Di Terima', 'Sedang Di Ajukan', 'Di Tolak'];

        $certificates = [];
        $batchSize = 200;
        $count = 0;

        foreach ($users as $user) {
            $projectsCount = rand(1, 3);

            for ($i = 0; $i < $projectsCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $tanggal = $faker->dateTimeBetween('-2 years', 'now');

                $certificates[] = [
                    'nama_sertifikat' => $certificateNames[array_rand($certificateNames)],
                    'lembaga_penerbit' => $issuers[array_rand($issuers)],
                    'tanggal_terbit' => $tanggal->format('Y-m-d'),
                    'link_sertifikat' => $links[array_rand($links)],
                    'id_mahasiswa' => $user->id,
                    'status_pengajuan' => $status,
                    'keterangan' => $status === 'Di Tolak' ? $faker->sentence(8) : null,
                    'is_active' => $status === 'Di Terima',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;

                if (count($certificates) >= $batchSize) {
                    Sertifikat::insert($certificates);
                    $certificates = [];
                }
            }
        }

        if (!empty($certificates)) {
            Sertifikat::insert($certificates);
        }

        $this->command->info("Berhasil membuat {$count} sertifikat dummy.");
    }
}