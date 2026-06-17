<?php
// app/Console/Commands/BackfillTranslations.php

namespace App\Console\Commands;

use App\Jobs\TranslateModelJob;
use App\Models\User;
use App\Models\Postingan;
use App\Models\Project;
use App\Models\LearningCorner;
use Illuminate\Console\Command;

class BackfillTranslations extends Command
{
    protected $signature = 'translations:backfill';
    protected $description = 'Antrekan job translate untuk semua data lama yang belum punya kolom translations';

    public function handle(): int
    {
        $models = [User::class, Postingan::class, Project::class, LearningCorner::class];

        foreach ($models as $modelClass) {
            $count = 0;
            $modelClass::whereNull('translations')
                ->orWhere('translations', '[]')
                ->chunkById(50, function ($records) use (&$count) {
                    foreach ($records as $record) {
                        TranslateModelJob::dispatch(get_class($record), $record->getKey());
                        $count++;
                    }
                });

            $this->info("{$modelClass}: {$count} job di-antrekan.");
        }

        return self::SUCCESS;
    }
}