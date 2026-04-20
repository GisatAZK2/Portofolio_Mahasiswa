<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class LocalizationService
{
    /**
     * Get all supported locales
     */
    public static function getSupportedLocales(): array
    {
        return config('app.supported_locales', ['id', 'en']);
    }

    /**
     * Get current locale
     */
    public static function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Get default/fallback locale
     */
    public static function getDefaultLocale(): string
    {
        return config('app.fallback_locale', 'id');
    }

    /**
     * Check if locale is supported
     */
    public static function isLocaleSupported(string $locale): bool
    {
        return in_array($locale, self::getSupportedLocales());
    }

    /**
     * Get URL with specific locale prefix
     * Example: locale_url('portfolio', 'en') -> /en/portfolio
     */
    public static function url(string $path = '', string $locale = null): string
    {
        $locale = $locale ?? self::getCurrentLocale();

        if (!self::isLocaleSupported($locale)) {
            $locale = self::getDefaultLocale();
        }

        // Remove leading slash from path
        $path = ltrim($path, '/');

        // Build full URL
        $url = '/' . $locale;
        if ($path !== '') {
            $url .= '/' . $path;
        }

        return $url;
    }

    /**
     * Generate localized route URL
     * Example: locale_route('portfolio.index', [], 'en') -> /en/portfolio
     */
    public static function route(string $name, array $parameters = [], string $locale = null): string
    {
        $locale = $locale ?? self::getCurrentLocale();

        if (!self::isLocaleSupported($locale)) {
            $locale = self::getDefaultLocale();
        }

        // Generate the route without locale
        $route = route($name, $parameters, false);

        // Remove leading slash
        $route = ltrim($route, '/');

        // Add locale prefix
        return '/' . $locale . '/' . $route;
    }

    /**
     * Get URL for switching to different locale
     * Example: locale_switch('en') -> /en/current-page
     */
    public static function switchUrl(string $locale = null): string
    {
        $locale = $locale ?? self::getDefaultLocale();

        if (!self::isLocaleSupported($locale)) {
            return self::url();
        }

        // Get current path without locale prefix
        $path = request()->path();
        $segments = explode('/', $path);

        // Remove locale prefix from path if exists
        if (self::isLocaleSupported($segments[0])) {
            array_shift($segments);
        }

        $cleanPath = implode('/', $segments);

        return self::url($cleanPath, $locale);
    }

    /**
     * Get all available locales with their URLs
     */
    public static function getAllLocaleUrls(): array
    {
        $locales = [];

        foreach (self::getSupportedLocales() as $locale) {
            $locales[$locale] = self::switchUrl($locale);
        }

        return $locales;
    }

    /**
     * Get locale name in Indonesian and English
     */
    public static function getLocaleName(string $locale): string
    {
        $names = [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
        ];

        return $names[$locale] ?? $locale;
    }

    /**
     * Get locale flag emoji
     */
    public static function getLocaleFlag(string $locale): string
    {
        $flags = [
            'id' => '🇮🇩',
            'en' => '🇬🇧',
        ];

        return $flags[$locale] ?? '';
    }
}
