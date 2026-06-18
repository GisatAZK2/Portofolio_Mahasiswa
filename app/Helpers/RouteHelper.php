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