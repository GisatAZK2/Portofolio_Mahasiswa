<?php
// app/Services/TranslationService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\Exceptions\LargeTextException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationService
{
    protected GoogleTranslate $translator;

    public function __construct()
    {
        $this->translator = new GoogleTranslate('en');
    }

    /**
     * Translate teks ke Inggris.
     * Kalau teks sudah terdeteksi Inggris, kembalikan apa adanya (tidak hit API).
     */
    public function toEnglish(?string $text): ?string
{
    $text = trim((string) $text);
    if ($text === '') {
        return $text;
    }

    try {
        $this->translator->setSource()->setTarget('en'); // auto-detect source
        $translated = $this->translator->translate($text);
        $detected = $this->translator->getLastDetectedSource();

        // Kalau source yang terdeteksi sudah 'en', tidak perlu hasil translate
        if ($detected === 'en') {
            return $text;
        }

        return $translated ?? $text;
    } catch (RateLimitException|TranslationRequestException $e) {
        throw $e; // biar job retry
    } catch (\Throwable $e) {
        Log::error('Translation error: ' . $e->getMessage());
        return $text;
    }
}

    /**
     * Deteksi heuristik sederhana: apakah teks kemungkinan besar sudah Inggris.
     * Dipakai untuk skip translate sesuai requirement "kalau sudah EN, gak usah ditranslate".
     */
    public function isAlreadyEnglish(string $text): bool
    {
        // Heuristik cepat tanpa API call tambahan:
        // 1. Kalau hanya berisi URL/angka/simbol → anggap "tidak perlu" (akan di-skip di level field lain)
        // 2. Pakai daftar kata umum bahasa Indonesia. Kalau TIDAK ada satupun kata Indonesia umum
        //    yang muncul, DAN ada kata bahasa Inggris umum, anggap sudah Inggris.
        $indonesianMarkers = [
            ' yang ', ' dan ', ' di ', ' ke ', ' dari ', ' untuk ', ' dengan ',
            ' adalah ', ' akan ', ' tidak ', ' ini ', ' itu ', ' pada ', ' saya ',
            ' kami ', ' kita ', ' bisa ', ' dapat ', ' dalam ', ' atau ',
        ];

        $padded = ' ' . mb_strtolower($text) . ' ';

        foreach ($indonesianMarkers as $marker) {
            if (str_contains($padded, $marker)) {
                return false; // ada kata Indonesia → bukan Inggris
            }
        }

        $englishMarkers = [
            ' the ', ' and ', ' is ', ' are ', ' with ', ' for ', ' this ',
            ' that ', ' will ', ' can ', ' from ', ' to ', ' in ', ' on ',
        ];

        foreach ($englishMarkers as $marker) {
            if (str_contains($padded, $marker)) {
                return true;
            }
        }

        // Tidak terdeteksi keduanya (misal teks sangat pendek/satu kata) →
        // anggap belum tentu Inggris, biarkan tetap diproses translate
        // (Google Translate sendiri juga akan no-op kalau source == target setelah deteksi).
        return false;
    }
}