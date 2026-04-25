<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;


if (!function_exists('lroute')) {
    /**
     * Localized Route Helper
     * Menggantikan route() dengan otomatis menambahkan locale
     */
    function lroute($name, $parameters = [], $absolute = true)
    {
        $parameters = array_merge(
            ['locale' => app()->getLocale()], 
            (array) $parameters
        );

        return route($name, $parameters, $absolute);
    }
}

function autoTranslate($text)
{
    if (!$text || trim($text) === '') {
        return $text;
    }

    $locale = app()->getLocale();

    if (in_array($locale, ['id', 'id_ID'])) {
        return $text;
    }

    $cacheKey = 'translate_' . md5($text . '_' . $locale);

    return Cache::remember($cacheKey, now()->addHours(12), function () use ($text, $locale) {
        try {
            $tr = new GoogleTranslate($locale);
            $result = $tr->translate($text);

            return $result;
        } catch (\Exception $e) {
            return $text;
        }
    });
}