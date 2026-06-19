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
    protected $signature = 'translations:backfill {--force : Antrekan ulang semua record, termasuk yang sudah punya translations}';
    protected $description = 'Antrekan job translate untuk semua data lama yang belum punya kolom translations';

    public function handle(): int
    {
        $models = [User::class, Postingan::class, Project::class, LearningCorner::class];

        foreach ($models as $modelClass) {
            $count = 0;

            $query = $modelClass::query();

            // Tanpa --force, hanya proses yang belum punya terjemahan
            if (!$this->option('force')) {
                $query->where(function ($q) {
                    $q->whereNull('translations')
                      ->orWhereRaw("JSON_TYPE(translations) IS NULL")
                      ->orWhereRaw("JSON_TYPE(translations) = 'NULL'")
                      ->orWhereRaw("translations = 'null'")
                      ->orWhereRaw("translations = '[]'")
                      ->orWhereRaw("translations = '{}'")
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(translations, '$.en')) IS NULL");
                });
            }

            $query->chunkById(50, function ($records) use (&$count) {
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