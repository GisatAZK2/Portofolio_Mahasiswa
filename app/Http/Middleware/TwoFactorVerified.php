<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            return $next($request);
        }

        // Cek apakah sedang dalam proses 2FA
        $userId = Session::get('2fa_user_id');
        $isVerified = Session::get('2fa_verified', false);
        
        if ($userId && !$isVerified) {
            // Belum verifikasi 2FA, redirect ke halaman verifikasi
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}