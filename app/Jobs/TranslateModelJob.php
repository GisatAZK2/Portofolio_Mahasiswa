<?php
// app/Jobs/TranslateModelJob.php

namespace App\Jobs;

use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TranslateModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10; // detik, dinaikkan tiap retry lewat backoff()

    public function __construct(
        public string $modelClass,
        public int|string $modelId
    ) {
        $this->onQueue('translations');
    }

    public function backoff(): array
    {
        return [10, 30, 60]; // retry ke-1: 10s, ke-2: 30s, ke-3: 60s
    }

    public function handle(TranslationService $translator): void
    {
        $model = $this->modelClass::find($this->modelId);

        if (!$model) {
            return; // record sudah dihapus, tidak perlu translate
        }

        $fields = method_exists($model, 'getTranslatableFields')
            ? $model->getTranslatableFields()
            : [];

        $translatedEn = $model->translations['en'] ?? [];

        try {
            foreach ($fields as $field) {
                $value = $model->{$field};

                if (is_array($value)) {
                    // field json: bisa array-of-items (content) atau object multi-key (isi_content)
                    $translatedEn[$field] = $this->translateArrayField(
                        $value,
                        $translator,
                        $model->getTranslatableItemTypes() ?? null
                    );
                } else {
                    $translatedEn[$field] = $translator->toEnglish($value);
                }
            }

            $model->translations = array_merge($model->translations ?? [], [
                'en' => $translatedEn,
                'source_lang' => 'id',
                'translated_at' => Carbon::now()->toISOString(),
            ]);

            if ($model->isFillable('translation_status') || in_array('translation_status', $model->getFillable())) {
                $model->translation_status = 'done';
            }

            $model->saveQuietly();
        } catch (\Throwable $e) {
            Log::error("TranslateModelJob failed for {$this->modelClass}#{$this->modelId}: " . $e->getMessage());

            if (in_array('translation_status', $model->getFillable())) {
                $model->translation_status = 'failed';
                $model->saveQuietly();
            }

            throw $e; // biar Laravel retry sesuai $tries/backoff
        }
    }

    /**
     * Translate field json yang berbentuk array of items: [{type, content}, ...]
     * ATAU object asosiatif: {nama_project: "...", deskripsi: "...", link_github: "..."}
     */
    protected function translateArrayField(array $value, TranslationService $translator, ?array $allowedTypes): array
    {
        // Case 1: array-of-items dengan key 'type' & 'content' (Postingan.content, LearningCorner.content)
        if ($this->isListOfItems($value)) {
            return array_map(function ($item) use ($translator, $allowedTypes) {
                if (!is_array($item) || !isset($item['type'])) {
                    return $item;
                }

                $skipTypes = ['image', 'link', 'game_thumbnail'];

                if (in_array($item['type'], $skipTypes, true)) {
                    return $item; // jangan translate url/gambar
                }

                if ($allowedTypes && !in_array($item['type'], $allowedTypes, true)) {
                    return $item;
                }

                $item['content'] = $translator->toEnglish($item['content'] ?? null);
                return $item;
            }, $value);
        }

        // Case 2: object asosiatif (Project.isi_content) — translate key teks, skip yang jelas URL
        $urlLikeKeys = ['link_project', 'link_github', 'link_video', 'link', 'url'];
        $result = [];

        foreach ($value as $key => $val) {
            if (in_array($key, $urlLikeKeys, true) || !is_string($val)) {
                $result[$key] = $val;
                continue;
            }
            $result[$key] = $translator->toEnglish($val);
        }

        return $result;
    }

    protected function isListOfItems(array $value): bool
    {
        return array_is_list($value);
    }
}