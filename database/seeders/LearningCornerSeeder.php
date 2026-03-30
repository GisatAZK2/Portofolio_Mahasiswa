<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\LearningCorner;
use Faker\Factory as Faker;

class LearningCornerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $projects = Project::all();
        if ($projects->isEmpty()) {
            $this->command->error('Tidak ada project. Jalankan ProjectSeeder terlebih dahulu.');
            return;
        }

        $topicTemplates = [
            'Membangun API RESTful dengan Laravel',
            'Mengenal Eloquent Relationship di Laravel',
            'Membuat Tampilan Responsif dengan Tailwind CSS',
            'Menerapkan Autentikasi di Laravel',
            'Mengoptimalkan Query MySQL untuk Performance',
            'Integrasi OAuth Google Login',
            'Membuat Webhook untuk Notifikasi Otomatis',
            'Deploy Aplikasi Laravel ke Heroku',
            'Pemanfaatan Redis untuk Caching',
            'Membangun SPA Sederhana dengan Vue.js'
        ];

        $referenceLinks = [
            'https://laravel.com/docs/10.x/eloquent-relationships',
            'https://laravel.com/docs/10.x/controllers',
            'https://tailwindcss.com/docs/utility-first',
            'https://www.freecodecamp.org/news/rest-api-meaning-restful-api/',
            'https://www.digitalocean.com/community/tutorials/mysql-optimization-tips',
            'https://www.youtube.com/watch?v=ImtZ5yENzgE',
            'https://dev.to/ledehur/simple-laravel-deployment-guide-6cf',
            'https://www.smashingmagazine.com/2021/04/introduction-redis-cache/',
            'https://www.codecademy.com/articles/introduction-to-vue',
            'https://www.sitepoint.com/laravel-api-authentication/'
        ];

        $learningCorners = [];
        $batchSize = 200;
        $count = 0;

        foreach ($projects as $project) {
            $title = $topicTemplates[array_rand($topicTemplates)];
            $link = $referenceLinks[array_rand($referenceLinks)];

            $content = [
                ['type' => 'title', 'content' => $title],
                ['type' => 'paragraph', 'content' => 'Ringkasan materi: ' . $faker->sentence(12)],
                ['type' => 'link', 'content' => $link],
                ['type' => 'note', 'content' => 'Sumber referensi asli diambil dari internet.']
            ];

            $learningCorners[] = [
                'id_mahasiswa' => $project->id_mahasiswa,
                'project_id' => $project->id,
                'content' => json_encode($content),
                'tanggal' => $faker->dateTimeBetween('-90 days', 'now')->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            if (count($learningCorners) >= $batchSize) {
                LearningCorner::insert($learningCorners);
                $learningCorners = [];
            }
        }

        if (!empty($learningCorners)) {
            LearningCorner::insert($learningCorners);
        }

        $this->command->info("Berhasil membuat {$count} Learning Corner dummy.");
    }
}
