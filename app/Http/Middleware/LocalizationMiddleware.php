<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['id', 'en']);
        $defaultLocale = config('app.fallback_locale', 'id');

        // Skip localization for static files
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|txt|pdf|webp)$/i', $request->path())) {
            return $next($request);
        }

        // Get locale from URL path
        $segments = explode('/', trim($request->path(), '/'));
        $possibleLocale = $segments[0] ?? null;

        // Check if first segment is a valid locale
        if (in_array($possibleLocale, $supportedLocales)) {
            app()->setLocale($possibleLocale);
            // Store locale in session
            session(['locale' => $possibleLocale]);
        } else {
            // Check session/cookie for locale preference
            $locale = session('locale') ?? $request->cookie('locale') ?? $defaultLocale;

            // Ensure locale is valid
            if (!in_array($locale, $supportedLocales)) {
                $locale = $defaultLocale;
            }

            // Set locale
            app()->setLocale($locale);
            session(['locale' => $locale]);

            // Only redirect if path is not empty and not excluded
            $excludedPaths = ['api', 'login', 'register', 'pengajuan-akun', 'logout', 'toggle-sidebar'];

            if ($request->path() !== '' && !in_array($segments[0], $excludedPaths)) {
                // Redirect to add locale prefix
                return redirect('/' . $locale . '/' . $request->path());
            }
        }

        return $next($request);
    }
}

