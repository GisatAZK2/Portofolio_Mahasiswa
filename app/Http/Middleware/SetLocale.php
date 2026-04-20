<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        $supported = ['en', 'id']; // tambahkan locale lain kalau ada

        if (in_array($locale, $supported)) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);   // ← Ini yang paling penting
        } else {
            $default = config('app.locale', 'en');
            App::setLocale($default);
            URL::defaults(['locale' => $default]);
        }

        return $next($request);
    }
}