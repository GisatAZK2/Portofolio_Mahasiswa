<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckDatabaseRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Ambil role terbaru langsung dari database
            $currentRole = DB::table('users')
                ->where('id', $user->id)
                ->value('role');
            
            // Cek apakah role di session sama dengan di database
            $sessionRole = session('user_role');
            
            if ($sessionRole && $sessionRole !== $currentRole) {
                // Role berubah di database! Logout paksa
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('warning', 'Role akun Anda telah diubah. Silakan login kembali.');
            }
        }
        
        return $next($request);
    }
}