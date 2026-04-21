<?php

/**
 * Localization Helper Functions
 * Compatible with PHP 8.4+
 */

if (!function_exists('locale_url')) {
    /**
     * Generate URL with locale prefix
     */
    function locale_url(string $path = '', ?string $locale = null): string
    {
        return \App\Services\LocalizationService::url($path, $locale);
    }
}

if (!function_exists('locale_route')) {
    /**
     * Generate route URL with locale prefix
     */
    function locale_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        return \App\Services\LocalizationService::route($name, $parameters, $locale);
    }
}

if (!function_exists('locale_switch')) {
    /**
     * Get URL for switching locale
     */
    function locale_switch(?string $locale = null): string
    {
        return \App\Services\LocalizationService::switchUrl($locale);
    }
}

if (!function_exists('get_current_locale')) {
    /**
     * Get current locale
     */
    function get_current_locale(): string
    {
        return \App\Services\LocalizationService::getCurrentLocale();
    }
}

if (!function_exists('get_supported_locales')) {
    /**
     * Get all supported locales
     */
    function get_supported_locales(): array
    {
        return \App\Services\LocalizationService::getSupportedLocales();
    }
}

if (!function_exists('get_locale_name')) {
    /**
     * Get locale name
     */
    function get_locale_name(string $locale): string
    {
        return \App\Services\LocalizationService::getLocaleName($locale);
    }
}

if (!function_exists('get_locale_flag')) {
    /**
     * Get locale flag emoji
     */
    function get_locale_flag(string $locale): string
    {
        return \App\Services\LocalizationService::getLocaleFlag($locale);
    }
}

if (!function_exists('get_all_locale_urls')) {
    /**
     * Get all locale URLs for current page
     */
    function get_all_locale_urls(): array
    {
        return \App\Services\LocalizationService::getAllLocaleUrls();
    }
}