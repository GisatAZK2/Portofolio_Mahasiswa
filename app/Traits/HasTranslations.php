<?php
// app/Traits/HasTranslations.php

namespace App\Traits;

use App\Jobs\TranslateModelJob;

trait HasTranslations
{
    /**
     * Daftar field yang harus ditranslate.
     */
    public function getTranslatableFields(): array
    {
        return $this->translatableFields ?? [];
    }

    public function getTranslatableItemTypes(): array
    {
        return $this->translatableItemTypes ?? ['title', 'description', 'paragraph', 'note', 'text'];
    }

    public static function bootHasTranslations(): void
    {
        static::created(function ($model) {
            $model->queueTranslation();
        });

        static::updated(function ($model) {
            $fields = $model->getTranslatableFields();
            $changed = array_intersect($fields, array_keys($model->getChanges()));

            if (!empty($changed)) {
                $model->queueTranslation();
            }
        });
    }

    public function queueTranslation(): void
    {
        if (method_exists($this, 'setAttribute') && $this->isFillable('translation_status')) {
            $this->translation_status = 'pending';
            $this->saveQuietly();
        }

        TranslateModelJob::dispatch(static::class, $this->getKey());
    }

    /**
     * Ambil nilai field: gunakan terjemahan jika tersedia,
     * fallback ke nilai asli dari database.
     *
     * @param  string       $field   Nama field (misal: 'deskripsi', 'content')
     * @param  string|null  $locale  Paksa locale tertentu, null = pakai locale aktif
     * @return mixed
     */
    public function translated(string $field, ?string $locale = null): mixed
    {
        $locale = $locale ?? app()->getLocale();
        $original = $this->getAttribute($field);

        // Jika locale adalah bahasa default/asli, kembalikan nilai asli
        $defaultLocale = config('app.fallback_locale', 'id');
        if ($locale === $defaultLocale) {
            return $original;
        }

        // Ambil terjemahan dari kolom translations
        // Pastikan translations sudah di-cast ke array di model
        $translations = $this->getAttribute('translations');
        
        // Debug: pastikan translations adalah array
        if (is_string($translations)) {
            $translations = json_decode($translations, true);
        }
        
        if (!is_array($translations)) {
            return $original;
        }

        // Cek apakah ada terjemahan untuk locale ini
        if (!isset($translations[$locale]) || !is_array($translations[$locale])) {
            return $original;
        }

        // Cek apakah ada terjemahan untuk field ini
        if (!isset($translations[$locale][$field])) {
            return $original;
        }

        $translated = $translations[$locale][$field];

        // Untuk field array (content blocks), merge secara cerdas
        if (is_array($original) && is_array($translated)) {
            return $this->mergeTranslatedBlocks($original, $translated);
        }

        return $translated;
    }

    /**
     * Selalu kembalikan nilai asli dari database,
     * tidak peduli locale aktif.
     */
    public function originalValue(string $field): mixed
    {
        return $this->getAttribute($field);
    }

    /**
     * Cek apakah terjemahan tersedia untuk locale + field tertentu.
     */
    public function hasTranslation(string $field, ?string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();
        
        $translations = $this->getAttribute('translations');
        if (is_string($translations)) {
            $translations = json_decode($translations, true);
        }
        
        if (!is_array($translations)) {
            return false;
        }

        return isset($translations[$locale][$field])
            && !empty($translations[$locale][$field]);
    }

    /**
     * Merge block terjemahan ke dalam block original.
     */
    protected function mergeTranslatedBlocks(array $originalBlocks, array $translatedBlocks): array
    {
        $translatableTypes = $this->translatableItemTypes ?? [];

        if (empty($translatableTypes)) {
            return $translatedBlocks;
        }

        $translatedByIndex = [];
        foreach ($translatedBlocks as $i => $block) {
            $translatedByIndex[$i] = $block;
        }

        $result = [];
        foreach ($originalBlocks as $i => $block) {
            $type = $block['type'] ?? null;

            if ($type && in_array($type, $translatableTypes) && isset($translatedByIndex[$i])) {
                $mergedBlock = array_merge($block, [
                    'content' => $translatedByIndex[$i]['content'] ?? $block['content'],
                ]);
                $result[] = $mergedBlock;
            } else {
                $result[] = $block;
            }
        }

        return $result;
    }
}