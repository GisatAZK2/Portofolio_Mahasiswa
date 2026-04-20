<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
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
        return App::getLocale();
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
     * Generate URL with locale prefix (untuk link biasa)
     */
    public static function url(string $path = '', ?string $locale = null): string
    {
        $locale = $locale ?? self::getCurrentLocale();

        if (!self::isLocaleSupported($locale)) {
            $locale = self::getDefaultLocale();
        }

        $path = ltrim($path, '/');
        return '/' . $locale . ($path ? '/' . $path : '');
    }

    /**
     * Generate localized route (INI YANG PALING PENTING & SUDAH DIPERBAIKI)
     */
    public static function route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? self::getCurrentLocale();

        if (!self::isLocaleSupported($locale)) {
            $locale = self::getDefaultLocale();
        }

        // Tambahkan locale sebagai parameter (ini yang bikin otomatis)
        $parameters = array_merge(['locale' => $locale], $parameters);

        // Gunakan route() Laravel biasa (tidak perlu manual tambah prefix lagi)
        return route($name, $parameters);
    }

    /**
     * Get URL for switching locale (switch bahasa)
     */
    public static function switchUrl(?string $locale = null): string
    {
        $locale = $locale ?? self::getDefaultLocale();

        if (!self::isLocaleSupported($locale)) {
            $locale = self::getDefaultLocale();
        }

        $currentPath = Request::path();
        $segments = explode('/', ltrim($currentPath, '/'));

        // Hapus locale lama jika ada
        if (!empty($segments[0]) && self::isLocaleSupported($segments[0])) {
            array_shift($segments);
        }

        $cleanPath = implode('/', $segments);

        return self::url($cleanPath, $locale);
    }

    /**
     * Get all available locales with their URLs (untuk dropdown switcher)
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
     * Get locale name
     */
    public static function getLocaleName(string $locale): string
    {
        $names = [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
        ];

        return $names[$locale] ?? ucfirst($locale);
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