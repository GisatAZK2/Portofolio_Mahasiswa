<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passkey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class PasskeyController extends Controller
{
    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Generate options for 2FA passkey verification
     */
    public function twoFactorOptions(Request $request)
    {
        try {
            // Ambil user dari session (belum login)
            $userId = Session::get('2fa_user_id');
            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'User tidak ditemukan. Silakan login ulang.'
                ], 401);
            }

            // Cek apakah user punya passkey
            if ($user->passkeys()->count() === 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Anda belum memiliki passkey. Silakan login dengan password biasa.'
                ], 404);
            }

            // Generate challenge
            $challenge = random_bytes(32);
            $challengeBase64 = $this->base64UrlEncode($challenge);
            
            // Store challenge untuk verifikasi 2FA
            Session::put('webauthn_2fa_challenge', $challenge);
            Session::put('webauthn_2fa_user_id', $user->id);

            $rpId = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
            
            // Prepare allowed credentials
            $allowCredentials = [];
            foreach ($user->passkeys as $passkey) {
                $allowCredentials[] = [
                    'id' => $passkey->credential_id,
                    'type' => 'public-key',
                ];
            }

            return response()->json([
                'success' => true,
                'challenge' => $challengeBase64,
                'rpId' => $rpId,
                'allowCredentials' => $allowCredentials,
                'userVerification' => 'required',
                'timeout' => 60000,
            ]);
        } catch (\Exception $e) {
            Log::error('2FA options error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify 2FA passkey and complete login
     */
    public function twoFactorVerify(Request $request)
    {
        try {
            $storedChallenge = Session::get('webauthn_2fa_challenge');
            $storedUserId = Session::get('webauthn_2fa_user_id');
            
            // Ambil user dari session
            $user = User::find($storedUserId);

            if (!$storedChallenge || !$storedUserId || !$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Session expired. Silakan login ulang.'
                ], 400);
            }

            $credential = $request->input('credential');
            if (!$credential || !isset($credential['response']['clientDataJSON'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid credential data'
                ], 400);
            }

            // Verify challenge
            $clientDataJson = base64_decode($credential['response']['clientDataJSON']);
            $clientData = json_decode($clientDataJson, true);
            
            if (!$clientData || !isset($clientData['challenge'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid client data'
                ], 400);
            }
            
            $clientChallengeRaw = $this->base64UrlDecode($clientData['challenge']);
            
            if ($clientChallengeRaw !== $storedChallenge) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Challenge mismatch'
                ], 401);
            }

            // Find passkey
            $credentialId = $credential['id'];
            $passkey = Passkey::where('credential_id', $credentialId)
                ->where('user_id', $user->id)
                ->first();

            if (!$passkey) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Passkey tidak valid'
                ], 401);
            }

            // Update sign count
            $passkey->increment('sign_count');

            // Get remember me value from session
            $remember = Session::get('2fa_remember', false);

            // LOGIN USER after successful 2FA
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Clear all 2FA session data
            Session::forget([
                'webauthn_2fa_challenge', 
                'webauthn_2fa_user_id', 
                '2fa_user_id', 
                '2fa_requires_verification', 
                '2fa_remember'
            ]);
            
            // Set session that 2FA is verified
            Session::put('2fa_verified', true);

            // Redirect based on role
            $redirect = match($user->role) {
                'admin' => route('admin.index'),
                'dosen' => route('dosen.dashboard'),
                default => route('dashboard.me'),
            };

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil',
                'redirect' => $redirect
            ]);

        } catch (\Exception $e) {
            Log::error('2FA verify error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== REGULAR PASSKEY METHODS ====================

    public function registerOptions(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $challenge = random_bytes(32);
            $challengeBase64 = $this->base64UrlEncode($challenge);
            session(['webauthn_register_challenge' => $challenge]);

            $rpId = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

            return response()->json([
                'challenge' => $challengeBase64,
                'rp' => [
                    'name' => config('app.name', 'Portal Mahasiswa'),
                    'id' => $rpId,
                ],
                'user' => [
                    'id' => $this->base64UrlEncode((string) $user->id),
                    'name' => $user->username,
                    'displayName' => $user->nama_mahasiswa,
                ],
                'pubKeyCredParams' => [
                    ['type' => 'public-key', 'alg' => -7],
                    ['type' => 'public-key', 'alg' => -257],
                ],
                'authenticatorSelection' => [
                    'authenticatorAttachment' => 'platform',
                    'residentKey' => 'preferred',
                    'userVerification' => 'preferred',
                ],
                'attestation' => 'none',
                'timeout' => 60000,
                'excludeCredentials' => [],
            ]);
        } catch (\Exception $e) {
            Log::error('Register options error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function registerVerify(Request $request)
    {
        try {
            $user = $request->user();
            $storedChallenge = session('webauthn_register_challenge');

            if (!$user || !$storedChallenge) {
                return response()->json(['success' => false, 'message' => 'Session expired'], 400);
            }

            // Dummy mode for testing
            if ($request->input('dummy')) {
                $passkey = Passkey::create([
                    'user_id' => $user->id,
                    'name' => $request->input('name', 'Dummy Device'),
                    'credential_id' => 'dummy_' . uniqid(),
                    'public_key' => 'dummy',
                    'sign_count' => 0,
                ]);
                session()->forget('webauthn_register_challenge');
                return response()->json(['success' => true, 'message' => 'Passkey ditambahkan (mode testing)']);
            }

            $credential = $request->input('credential');
            if (!$credential || !isset($credential['response']['clientDataJSON'])) {
                return response()->json(['success' => false, 'message' => 'Invalid credential'], 400);
            }

            $clientDataJson = base64_decode($credential['response']['clientDataJSON']);
            $clientData = json_decode($clientDataJson, true);
            $clientChallengeRaw = $this->base64UrlDecode($clientData['challenge']);

            if ($clientChallengeRaw !== $storedChallenge) {
                return response()->json(['success' => false, 'message' => 'Challenge mismatch'], 400);
            }

            $passkey = Passkey::create([
                'user_id' => $user->id,
                'name' => $request->input('name', 'Device'),
                'credential_id' => $credential['id'],
                'public_key' => 'stored',
                'sign_count' => 0,
                'transports' => json_encode($credential['response']['transports'] ?? []),
            ]);

            session()->forget('webauthn_register_challenge');

            return response()->json([
                'success' => true,
                'message' => 'Passkey berhasil ditambahkan',
                'passkey' => ['id' => $passkey->id, 'name' => $passkey->name]
            ]);
        } catch (\Exception $e) {
            Log::error('Register verify error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function index(Request $request)
    {
        $passkeys = $request->user()->passkeys()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $passkeys->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'created_at' => $p->created_at->format('d M Y'),
                'created_at_humans' => $p->created_at->diffForHumans(),
            ]),
        ]);
    }
    
public function destroy(Request $request)
{
    try {
        // Ambil id dari query parameter
        $id = $request->query('id');
        
        if (!$id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'ID passkey diperlukan'], 400);
            }
            return redirect()->back()->with('error', 'ID passkey diperlukan');
        }
        
        // Cek user dari session/auth
        $user = $request->user();
        
        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Cari passkey milik user ini
        $passkey = Passkey::where('user_id', $user->id)->find($id);
        
        if (!$passkey) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Passkey tidak ditemukan'], 404);
            }
            return redirect()->back()->with('error', 'Passkey tidak ditemukan');
        }
        
        $passkey->delete();
        
        // Jika request dari form (bukan JSON)
        if (!$request->wantsJson()) {
            $locale = app()->getLocale();
            return redirect()->route('webauthn.passkeys.index', ['locale' => $locale])
                ->with('success', 'Passkey berhasil dihapus');
        }
        
        return response()->json(['success' => true, 'message' => 'Passkey dihapus']);
        
    } catch (\Exception $e) {
        Log::error('Delete passkey error: ' . $e->getMessage());
        
        if (!$request->wantsJson()) {
            return redirect()->back()->with('error', 'Gagal menghapus passkey: ' . $e->getMessage());
        }
        
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}