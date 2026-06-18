<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Lama cookie locale disimpan (hari).
     */
    protected int $cookieDays = 365;

    /**
     * Tentukan & terapkan locale aktif untuk request ini.
     *
     * Urutan prioritas:
     * 1. Segmen locale di URL (mis. /en/dashboard) — kalau valid, ini paling otoritatif
     *    karena eksplisit ditulis user/link.
     * 2. Cookie 'lang' — diisi oleh JS saat user memilih bahasa lewat dropdown,
     *    supaya pilihan ini "menempel" walau pindah ke halaman tanpa prefix locale.
     * 3. Session 'locale' — fallback kalau cookie belum/tidak terkirim (mis. request pertama
     *    setelah set cookie, sebelum browser benar-benar menyimpannya).
     * 4. APP_LOCALE dari config — fallback terakhir.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = $this->supportedLocales();

        $locale = $this->resolveFromQuery($request, $supported)
            ?? $this->resolveFromUrlSegment($request, $supported)
            ?? $this->resolveFromCookie($request, $supported)
            ?? $this->resolveFromSession($request, $supported)
            ?? config('app.locale');

        App::setLocale($locale);

        // Supaya route('nama.route') yang punya parameter {locale} otomatis
        // terisi locale aktif tanpa perlu menulis manual
        // ['locale' => app()->getLocale()] di setiap pemanggilan route().
        URL::defaults(['locale' => $locale]);

        // Simpan balik supaya konsisten di request-request berikutnya,
        // termasuk halaman yang tidak punya prefix locale di URL.
        session(['locale' => $locale]);

        $response = $next($request);

        // Refresh cookie supaya umurnya tidak habis & selalu sinkron
        // dengan locale yang benar-benar aktif di request ini.
        if (method_exists($response, 'withCookie')) {
            $response->withCookie(cookie('lang', $locale, $this->cookieDays * 24 * 60));
        }

        return $response;
    }

    protected function resolveFromUrlSegment(Request $request, array $supported): ?string
    {
        $segment = $request->segment(1);

        return ($segment && in_array($segment, $supported, true)) ? $segment : null;
    }

    protected function resolveFromCookie(Request $request, array $supported): ?string
    {
        $cookieLocale = $request->cookie('lang');

        return ($cookieLocale && in_array($cookieLocale, $supported, true)) ? $cookieLocale : null;
    }

    protected function resolveFromSession(Request $request, array $supported): ?string
    {
        $sessionLocale = $request->session()->get('locale');

        return ($sessionLocale && in_array($sessionLocale, $supported, true)) ? $sessionLocale : null;
    }

    protected function supportedLocales(): array
    {
        return config('app.supported_locales', explode(',', env('SUPPORTED_LOCALES', 'id,en')));
    }

    protected function resolveFromQuery(Request $request, array $supported): ?string
    {
        $queryLocale = $request->query('locale');
        return ($queryLocale && in_array($queryLocale, $supported, true)) ? $queryLocale : null;
    }
}