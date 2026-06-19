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
    public int $backoff = 10;

    /**
     * @param  string        $modelClass    Nama class model (misal: App\Models\Project)
     * @param  int|string    $modelId       Primary key model
     * @param  array         $changedFields Field yang berubah saat update.
     *                                      Kosong = semua field di-translate (saat create).
     */
    public function __construct(
        public string $modelClass,
        public int|string $modelId,
        public array $changedFields = [],
    ) {
        $this->onQueue('translations');
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(TranslationService $translator): void
    {
        $model = $this->modelClass::find($this->modelId);

        if (!$model) {
            return; // record sudah dihapus, tidak perlu translate
        }

        $allFields = method_exists($model, 'getTranslatableFields')
            ? $model->getTranslatableFields()
            : [];

        // Tentukan field mana yang perlu ditranslate:
        // - Jika changedFields kosong (create), translate semua field.
        // - Jika changedFields ada isinya (update), hanya translate field yang berubah.
        $fieldsToTranslate = empty($this->changedFields)
            ? $allFields
            : array_intersect($allFields, $this->changedFields);

        if (empty($fieldsToTranslate)) {
            return;
        }

        // Ambil terjemahan yang sudah ada dari DB (fresh, bukan dari cache model)
        $currentTranslations = $model->translations ?? [];
        if (is_string($currentTranslations)) {
            $currentTranslations = json_decode($currentTranslations, true) ?? [];
        }

        $translatedEn = $currentTranslations['en'] ?? [];

        // ---------------------------------------------------------------
        // Cleanup translations lama untuk field yang berubah.
        // Ini dulu dilakukan di bootHasTranslations (synchronous, di dalam
        // request lifecycle). Sekarang dipindah ke sini supaya tidak nge-lag.
        // ---------------------------------------------------------------
        if (!empty($this->changedFields)) {
            foreach ($this->changedFields as $field) {
                unset($translatedEn[$field]);
            }
            // Hapus juga translated_at agar frontend tahu terjemahan sedang pending
            unset($currentTranslations['translated_at']);
        }

        try {
            foreach ($fieldsToTranslate as $field) {
                $value = $model->{$field};

                if (is_array($value)) {
                    $translatedEn[$field] = $this->translateArrayField(
                        $value,
                        $translator,
                        method_exists($model, 'getTranslatableItemTypes')
                            ? $model->getTranslatableItemTypes()
                            : null
                    );
                } else {
                    $translatedEn[$field] = $translator->toEnglish($value);
                }
            }

            $model->translations = array_merge($currentTranslations, [
                'en'            => $translatedEn,
                'source_lang'   => 'id',
                'translated_at' => Carbon::now()->toISOString(),
            ]);

            $model->saveQuietly();
        } catch (\Throwable $e) {
            Log::error("TranslateModelJob failed for {$this->modelClass}#{$this->modelId}: " . $e->getMessage());

            throw $e; // biar Laravel retry sesuai $tries/backoff
        }
    }

    /**
     * Translate field json yang berbentuk array of items: [{type, content}, ...]
     * ATAU object asosiatif: {nama_project: "...", deskripsi: "...", link_github: "..."}
     */
    protected function translateArrayField(array $value, TranslationService $translator, ?array $allowedTypes): array
    {
        // Case 1: array-of-items dengan key 'type' & 'content'
        if ($this->isListOfItems($value)) {
            return array_map(function ($item) use ($translator, $allowedTypes) {
                if (!is_array($item) || !isset($item['type'])) {
                    return $item;
                }

                $skipTypes = ['image', 'link', 'game_thumbnail'];

                if (in_array($item['type'], $skipTypes, true)) {
                    return $item;
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