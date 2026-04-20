<?php

if (!function_exists('lroute')) {
    /**
     * Localized Route Helper
     * Menggantikan route() dengan otomatis menambahkan locale
     */
    function lroute($name, $parameters = [], $absolute = true)
    {
        // Gabungkan locale saat ini dengan parameter lain
        $parameters = array_merge(
            ['locale' => app()->getLocale()], 
            (array) $parameters
        );

        return route($name, $parameters, $absolute);
    }
}