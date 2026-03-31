<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = User::where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')->get();
        if ($users->isEmpty()) {
            $this->command->error('Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat project dummy untuk setiap user...');

        $projectTemplates = [
            'Portfolio Website',
            'E-commerce Store',
            'Landing Page',
            'Blog Platform',
            'Task Manager',
            'Learning App',
            'Event Organizer',
            'Social Dashboard',
            'Booking System',
            'Gallery Showcase'
        ];

        $githubTemplates = [
            'https://github.com/octocat/Hello-World',
            'https://github.com/laravel/laravel',
            'https://github.com/vuejs/vue',
            'https://github.com/tailwindlabs/tailwindcss',
            'https://github.com/facebook/react',
            'https://github.com/django/django',
            'https://github.com/nodejs/node',
            'https://github.com/expressjs/express',
            'https://github.com/nextjs/next.js',
            'https://github.com/angular/angular'
        ];

        $youtubeTemplates = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://www.youtube.com/watch?v=3fumBcKC6RE',
            'https://www.youtube.com/watch?v=9bZkp7q19f0',
            'https://www.youtube.com/watch?v=5NV6Rdv1a3I',
            'https://www.youtube.com/watch?v=hY7m5jjJ9mM',
            'https://www.youtube.com/watch?v=OPf0YbXqDm0',
            'https://www.youtube.com/watch?v=60ItHLz5WEA',
            'https://www.youtube.com/watch?v=YQHsXMglC9A',
            'https://www.youtube.com/watch?v=CevxZvSJLk8',
            'https://www.youtube.com/watch?v=kXYiU_JCYtU'
        ];

        $projects = [];
        $batchSize = 200;
        $counter = 0;

        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $template = $projectTemplates[array_rand($projectTemplates)];
                $projectName = sprintf('%s %s #%d', $template, $user->nama_mahasiswa ?? $user->username, $i);
                $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($projectName));
                $slug = trim($slug, '-');

                $projectLinks = [
                    'link_project' => 'https://demo.example.com/' . $slug,
                    'link_github' => $githubTemplates[array_rand($githubTemplates)],
                    'link_video' => $youtubeTemplates[array_rand($youtubeTemplates)]
                ];

                $content = [
                    'nama_project' => $projectName,
                    'deskripsi' => $faker->sentence(12),
                    'link_project' => $projectLinks['link_project'],
                    'link_github' => $projectLinks['link_github'],
                    'link_video' => $projectLinks['link_video']
                ];

                $startDate = $faker->dateTimeBetween('-120 days', 'now');
                $endDate = $faker->dateTimeBetween($startDate, '+90 days');

                $projects[] = [
                    'isi_content' => json_encode($content),
                    'tanggal_mulai' => $startDate->format('Y-m-d'),
                    'tanggal_akhir' => $endDate->format('Y-m-d'),
                    'leader_id' => $user->id,
                    'id_mahasiswa' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $counter++;

                if (count($projects) >= $batchSize) {
                    Project::insert($projects);
                    $projects = [];
                }
            }
        }

        if (!empty($projects)) {
            Project::insert($projects);
        }

        $this->command->info("Berhasil membuat {$counter} project dummy.");
    }
}
