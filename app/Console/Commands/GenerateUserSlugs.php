<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\User;

class GenerateUserSlugs extends Command
{
    protected $signature   = 'users:generate-slugs';
    protected $description = 'Generate slug SEO-friendly untuk semua user yang belum punya slug';

    public function handle(): int
    {
        $users = User::whereNull('slug')
            ->whereNotNull('nama_mahasiswa')
            ->where('role', 'mahasiswa')
            ->get();

        if ($users->isEmpty()) {
            $this->info('Semua user sudah punya slug.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $user->slug = $this->generateUniqueSlug($user->nama_mahasiswa, $user->id);
            $user->saveQuietly(); // saveQuietly supaya tidak trigger event lain
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Berhasil generate {$users->count()} slug.");

        return self::SUCCESS;
    }

    private function generateUniqueSlug(string $name, int $exceptId): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (User::where('slug', $slug)->where('id', '!=', $exceptId)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}