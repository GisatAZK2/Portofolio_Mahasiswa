<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passkey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PasskeyController extends Controller
{
    /**
     * Get authentication options for login
     */
    public function authenticationOptions(Request $request)
    {
        try {
            $emailOrUsername = $request->input('email');
            
            if (!$emailOrUsername) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau username diperlukan'
                ], 400);
            }
            
            // Find user by email or username
            $user = User::where('email', $emailOrUsername)
                        ->orWhere('username', $emailOrUsername)
                        ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            
            // Check if user has passkeys
            if (!$user->passkeys()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User belum memiliki passkey. Silakan login dengan password terlebih dahulu untuk menambahkan passkey.'
                ], 404);
            }
            
            // Generate random challenge (32 bytes = 64 hex chars)
            $challenge = bin2hex(random_bytes(32));
            
            // Store challenge in session for verification
            session(['webauthn_login_challenge' => $challenge]);
            
            $rpId = parse_url(config('app.url'), PHP_URL_HOST);
            
            // Get user's credentials
            $allowCredentials = [];
            foreach ($user->passkeys as $passkey) {
                $allowCredentials[] = [
                    'id' => base64_encode(base64_decode($passkey->credential_id)),
                    'type' => 'public-key',
                    'transports' => json_decode($passkey->transports ?? '[]', true),
                ];
            }
            
            $options = [
                'challenge' => $challenge,
                'rpId' => $rpId ?: 'localhost',
                'allowCredentials' => $allowCredentials,
                'userVerification' => 'required',
                'timeout' => 60000,
            ];
            
            return response()->json($options);
            
        } catch (\Exception $e) {
            Log::error('WebAuthn authentication options error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses request: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verify authentication
     */
    public function authenticate(Request $request)
    {
        try {
            $credential = $request->input('credential');
            $challenge = session('webauthn_login_challenge');
            
            if (!$challenge) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please try again.'
                ], 400);
            }
            
            // Parse credential ID
            $credentialId = base64_decode($credential['id']);
            $credentialIdBase64 = base64_encode($credentialId);
            
            // Find passkey by credential ID
            $passkey = Passkey::where('credential_id', $credentialIdBase64)->first();
            
            if (!$passkey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Passkey tidak ditemukan'
                ], 401);
            }
            
            // Get user
            $user = $passkey->user;
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }
            
            // Check user status
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi admin.'
                ], 401);
            }
            
            if ($user->status_pengajuan !== 'Di Terima') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum disetujui. Status: ' . $user->status_pengajuan
                ], 401);
            }
            
            // Validate client data
            $clientDataJSON = base64_decode($credential['response']['clientDataJSON']);
            $clientData = json_decode($clientDataJSON, true);
            
            if (!$clientData) {
                throw new \Exception('Invalid client data');
            }
            
            // Check challenge match
            $clientChallenge = base64_decode(strtr($clientData['challenge'], '-_', '+/'));
            $clientChallengeHex = bin2hex($clientChallenge);
            
            if ($clientChallengeHex !== $challenge) {
                throw new \Exception('Challenge mismatch');
            }
            
            // Check origin
            $origin = $clientData['origin'];
            $expectedOrigin = config('app.url');
            $expectedOrigin = rtrim($expectedOrigin, '/');
            
            $expectedOriginParsed = parse_url($expectedOrigin);
            $expectedHost = $expectedOriginParsed['host'] ?? $expectedOrigin;
            
            if (!str_contains($origin, $expectedHost) && app()->environment('production')) {
                throw new \Exception('Origin mismatch');
            }
            
            // Update sign count
            $authenticatorData = base64_decode($credential['response']['authenticatorData']);
            $newSignCount = $this->extractSignCount($authenticatorData);
            
            if ($newSignCount > 0 && $newSignCount <= (int)$passkey->sign_count) {
                Log::warning('Possible replay attack detected', [
                    'user_id' => $user->id,
                    'old_count' => $passkey->sign_count,
                    'new_count' => $newSignCount
                ]);
            }
            
            $passkey->update(['sign_count' => $newSignCount]);
            
            // Login user
            Auth::login($user);
            $request->session()->regenerate();
            
            // Clear session
            session()->forget('webauthn_login_challenge');
            
            // Determine redirect URL based on role
            $redirect = match($user->role) {
                'admin' => route('admin.index'),
                'dosen' => route('dosen.dashboard'),
                default => route('dashboard.me'),
            };
            
            return response()->json([
                'success' => true,
                'redirect' => $redirect,
                'user' => [
                    'name' => $user->nama_mahasiswa,
                    'role' => $user->role
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('WebAuthn authentication failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi: ' . $e->getMessage()
            ], 401);
        }
    }
    
    /**
     * Get registration options for new passkey
     */
    public function registerOptions(Request $request)
{
    $user = $request->user();
    
    // Untuk localhost, gunakan 'localhost' sebagai rpId
    $rpId = 'localhost';
    $origin = 'http://localhost:8000';
    
    // Jika pakai HTTPS atau domain lain
    if (app()->environment('production')) {
        $rpId = parse_url(config('app.url'), PHP_URL_HOST);
    }
    
    return response()->json([
        'challenge' => bin2hex(random_bytes(32)),
        'rp' => [
            'name' => config('app.name', 'Portal Mahasiswa'),
            'id' => $rpId,
        ],
        'user' => [
            'id' => base64_encode((string) $user->id),
            'name' => $user->username,
            'displayName' => $user->nama_mahasiswa,
        ],
        'pubKeyCredParams' => [
            ['type' => 'public-key', 'alg' => -7],   // ES256
            ['type' => 'public-key', 'alg' => -257], // RS256
        ],
        'authenticatorSelection' => [
            'authenticatorAttachment' => 'cross-platform', // Ubah dari 'platform' ke 'cross-platform'
            'residentKey' => 'discouraged', // Ubah dari 'required' ke 'discouraged'
            'userVerification' => 'preferred', // Ubah dari 'required' ke 'preferred'
        ],
        'attestation' => 'none',
        'timeout' => 60000,
    ]);
}

    /**
     * Get existing credential IDs to exclude
     */
    private function getExcludedCredentials($user)
    {
        $excluded = [];
        foreach ($user->passkeys as $passkey) {
            $excluded[] = [
                'id' => base64_encode(base64_decode($passkey->credential_id)),
                'type' => 'public-key',
            ];
        }
        return $excluded;
    }
    
    /**
     * Verify and save new passkey
     */
    public function registerVerify(Request $request)
    {
        try {
            $user = $request->user();
            $challenge = session('webauthn_register_challenge');
            
            if (!$challenge) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please try again.'
                ], 400);
            }
            
            $credential = $request->input('credential');
            $deviceName = $request->input('name', 'My Device');
            
            // Parse credential ID
            $credentialId = base64_decode($credential['id']);
            
            // Check if credential ID already exists
            $existingPasskey = Passkey::where('credential_id', base64_encode($credentialId))->first();
            if ($existingPasskey) {
                throw new \Exception('Passkey already registered');
            }
            
            // Validate client data
            $clientDataJSON = base64_decode($credential['response']['clientDataJSON']);
            $clientData = json_decode($clientDataJSON, true);
            
            if (!$clientData) {
                throw new \Exception('Invalid client data');
            }
            
            // Check challenge match
            $clientChallenge = base64_decode(strtr($clientData['challenge'], '-_', '+/'));
            $clientChallengeHex = bin2hex($clientChallenge);
            
            if ($clientChallengeHex !== $challenge) {
                throw new \Exception('Challenge mismatch');
            }
            
            // Check origin
            $origin = $clientData['origin'];
            $expectedOrigin = config('app.url');
            $expectedOrigin = rtrim($expectedOrigin, '/');
            
            $expectedOriginParsed = parse_url($expectedOrigin);
            $expectedHost = $expectedOriginParsed['host'] ?? $expectedOrigin;
            
            if (!str_contains($origin, $expectedHost) && app()->environment('production')) {
                throw new \Exception('Origin mismatch');
            }
            
            // Extract public key from attestation
            $attestationData = base64_decode($credential['response']['attestationObject']);
            $publicKey = $this->extractPublicKeyFromAttestation($attestationData);
            
            // Check if user already has 5 passkeys (limit)
            $passkeyCount = Passkey::where('user_id', $user->id)->count();
            if ($passkeyCount >= 5) {
                throw new \Exception('Maksimal 5 passkey per akun');
            }
            
            // Save passkey
            $passkey = Passkey::create([
                'user_id' => $user->id,
                'name' => $deviceName,
                'credential_id' => base64_encode($credentialId),
                'public_key' => base64_encode($publicKey),
                'sign_count' => '0',
                'transports' => isset($credential['response']['transports']) 
                    ? json_encode($credential['response']['transports']) 
                    : null,
                'aaguid' => $this->extractAAGUID($attestationData),
            ]);
            
            // Clear session
            session()->forget('webauthn_register_challenge');
            
            return response()->json([
                'success' => true,
                'message' => 'Passkey berhasil ditambahkan',
                'passkey' => [
                    'id' => $passkey->id,
                    'name' => $passkey->name,
                    'created_at' => $passkey->created_at->diffForHumans(),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('WebAuthn registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi passkey: ' . $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Get user's passkeys list
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }
            
            $passkeys = $user->passkeys()->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $passkeys->map(function($passkey) {
                    return [
                        'id' => $passkey->id,
                        'name' => $passkey->name,
                        'created_at' => $passkey->created_at->format('d M Y'),
                        'created_at_humans' => $passkey->created_at->diffForHumans(),
                        'is_synced' => $this->isSyncedPasskey($passkey->aaguid),
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get passkeys error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data passkey'
            ], 500);
        }
    }
    
    /**
     * Delete passkey
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $passkey = Passkey::where('user_id', $user->id)
                ->findOrFail($id);
            
            $passkey->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Passkey berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete passkey error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus passkey'
            ], 500);
        }
    }
    
    /**
     * Check if passkey is synced (iCloud, Google, etc.)
     */
    private function isSyncedPasskey($aaguid)
    {
        $syncAAGUIDs = [
            '00000000-0000-0000-0000-000000000000',
            '6028b017-b1d4-4c02-b4b3-afcdafc96bb2', // iCloud Keychain
            'ea9b8d66-4d01-1d21-3ce4-b6b48cb575d4', // Google Password Manager
        ];
        return in_array($aaguid, $syncAAGUIDs);
    }
    
    /**
     * Extract sign count from authenticator data
     */
    private function extractSignCount($authenticatorData)
    {
        // Sign count is at offset 33 (after RP ID hash (32) + flags (1))
        if (strlen($authenticatorData) >= 37) {
            $signCountBytes = substr($authenticatorData, 33, 4);
            return unpack('N', $signCountBytes)[1];
        }
        return 0;
    }
    
    /**
     * Simple public key extraction (for demo purposes)
     * In production, use proper WebAuthn library validation
     */
    private function extractPublicKeyFromAttestation($attestationData)
    {
        // Simplified - store a hash of the attestation data
        return substr(hash('sha256', $attestationData), 0, 64);
    }
    
    /**
     * Extract AAGUID from attestation
     */
    private function extractAAGUID($attestationData)
    {
        // Simplified - return default
        return '00000000-0000-0000-0000-000000000000';
    }
}