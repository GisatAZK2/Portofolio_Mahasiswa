<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use App\Models\Keahlian_Tambahan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\ImageConversionService;
use App\Http\Controllers\v1\NotificationController;
use Carbon\Carbon;

class UserController extends Controller
{
    // Tampilkan form register Complete (untuk user yang login dengan password sementara)
    public function showCompleteRegistration()
    {
        $tempUserData = session('temp_user_data');
        $userId = session('temp_user_id');

        if (!$tempUserData || !$userId) {
            return redirect()->route('login')
                ->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Data user tidak ditemukan.');
        }

        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        $jurusanName = $user->jurusan ? $user->jurusan->nama_jurusan : '-';
        $keahlianName = $user->keahlian ? $user->keahlian->nama_keahlian : '-';
        $angkatanName = $user->angkatan ? $user->angkatan->nama_angkatan : '-';

        return view('auth.complete-registration', compact('tempUserData', 'user', 'jurusans', 'keahlians', 'angkatans', 'jurusanName', 'keahlianName', 'angkatanName'));
    }

    // Proses register (normal flow)
    public function register(Request $request)
    {
        // Check if this is completing incomplete registration
        $isCompleting = $request->has('completing_registration') && $request->completing_registration == 'true';

        if ($isCompleting) {
            // Get user from session
            $userId = $request->session()->get('temp_user_id');
            $user = User::find($userId);

            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Sesi tidak valid. Silakan login kembali.');
            }

            // Validate only the additional fields
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/'],
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase(), 'regex:/^\S*$/'],
                'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
                'description' => ['nullable', 'string', 'max:500'],
                'video_url' => ['nullable', 'url', 'max:255'],
                'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'], // Max 10MB
                'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5012']
            ]);

            // Handle background image upload
            $backgroundPath = null;
            if ($request->hasFile('background_image')) {
                $backgroundPath = ImageConversionService::storeWebp($request->file('background_image'), 'backgrounds');
            }

            // Handle Photo_profile
            $photoProfilePath = null;
            if ($request->hasFile('photo_profile')) {
                $photoProfilePath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
            }

            // Update user
            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'] ?? $user->email,
                'password' => Hash::make($validated['password']),
                'deskripsi' => $validated['description'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'background_url' => $backgroundPath,
                'photo_profile' => $photoProfilePath,
            ]);

            // Kirim notifikasi ke admin
            $this->sendNotifications($user);

            // Clear session
            $request->session()->forget(['temp_user_id', 'temp_user_data', 'incomplete_registration_data']);

            return redirect()->route('login')
                ->with('success', 'Data berhasil dilengkapi! Silakan Login Dengan Password Yang Sudah Anda Buat');
        }

        // Normal registration flow
        $validated = $request->validate([
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:50', 'unique:users,nim'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'id_jurusan' => ['required', 'exists:jurusan,id_jurusan'],
            'id_keahlian' => ['required', 'exists:keahlian,id_keahlian'],
            'id_angkatan' => ['required', 'exists:angkatan,id'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);

        $validated['role'] = 'mahasiswa';
        $validated['is_active'] = true;
        $validated['password'] = null; // Password will be set later

        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        $user = User::create($validated);
        $user->load('jurusan', 'angkatan', 'keahlian');

        // Kirim notifikasi ke admin
        $this->sendNotifications($user);

        $newCount = $totalAttempts + 1;
        Cache::put("registration_count_{$guestIdentifier}", $newCount, now()->addHours(24));
        $request->session()->put('registration_attempts', $newCount);
        $request->session()->put('last_registration_time', now());

        return redirect()->route('login')
            ->with('success', 'Pengajuan telah berhasil dibuat, silahkan tunggu admin/dosen angkatan anda menyetujui.');
    }

    private function sendNotifications($user)
    {
        $jurusanNama = $user->jurusan->nama_jurusan ?? '-';
        $angkatanNama = $user->angkatan->nama_angkatan ?? '-';

        NotificationController::add(
            'user-registered',
            [
                'title' => 'Mahasiswa Menyelesaikan Pendaftaran',
                'message' => "Mahasiswa telah menyelesaikan pendaftarannya: {$user->nama_mahasiswa} ({$jurusanNama} - {$angkatanNama})",
                'user_id' => $user->id,
                'user_name' => $user->nama_mahasiswa,
                'link' => url(app()->getLocale() . '/admin/manageUser'),
            ],
            'high'
        );
    }

    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login (email ATAU username ATAU NIM) dengan 2FA Passkey
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cek apakah login menggunakan email, username, atau NIM
        $fieldType = 'email';
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } else {
            // Cek apakah login adalah NIM (hanya angka)
            if (preg_match('/^\d+$/', $request->login)) {
                $fieldType = 'nim';
            } else {
                $fieldType = 'username';
            }
        }

        $user = User::where($fieldType, $request->login)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Akun tidak ditemukan.',
            ])->onlyInput('login');
        }

        // Cek status user
        if ($user->is_active == 0 && empty($user->status_pengajuan)) {
            return back()->withErrors([
                'login' => 'AKUN_DIBLOKIR',
            ])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Sedang Di Ajukan') {
            return back()->withErrors([
                'login' => 'PENGAJUAN_DIPROSES',
            ])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Di Tolak') {
            return back()->withErrors([
                'login' => 'PENGAJUAN_DITOLAK',
            ])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Di Terima' && $user->is_active == 0) {
            return back()->withErrors([
                'login' => 'AKUN_DIBLOKIR',
            ])->onlyInput('login');
        }

        // ============ PASSWORD VALIDATION ============
        $passwordValid = false;

        // Check if password is empty in database
        if (empty($user->password)) {
            // Use tanggal_lahir as temporary password
            $tempPassword = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null;

            if ($tempPassword && $request->password === $tempPassword) {
                $passwordValid = true;

                // Store user data in session for completion
                $userData = [
                    'id' => $user->id,
                    'nama_mahasiswa' => $user->nama_mahasiswa,
                    'nim' => $user->nim,
                    'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null,
                    'email' => $user->email,
                    'id_jurusan' => $user->id_jurusan,
                    'id_keahlian' => $user->id_keahlian,
                    'id_angkatan' => $user->id_angkatan,
                    'photo_profile' => $user->photo_profile,
                ];

                session([
                    'temp_user_id' => $user->id,
                    'temp_user_data' => $userData,
                    'incomplete_registration_data' => $userData
                ]);

                // Redirect to complete registration
                return redirect()->route('register.complete')
                    ->with('warning', 'Silakan lengkapi data akun Anda (username, password, dll) untuk melanjutkan.');
            }
        } else {
            // Normal password check
            if (Hash::check($request->password, $user->password)) {
                $passwordValid = true;
            }
        }

        if (!$passwordValid) {
            return back()->withErrors([
                'login' => 'Password yang Anda masukkan salah.',
            ])->onlyInput('login');
        }

        // ============ 2FA PASSKEY ============
        // Cek apakah user memiliki passkey
        $hasPasskey = $user->passkeys()->count() > 0;

        if ($hasPasskey) {
            // Simpan user ID ke session untuk verifikasi 2FA
            session(['2fa_user_id' => $user->id]);
            session(['2fa_requires_verification' => true]);
            session(['2fa_remember' => $request->boolean('remember')]);

            // Redirect ke halaman verifikasi passkey
            return redirect()->route('2fa.verify');
        }

        // ============ Tanpa 2FA (langsung login) ============
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.index')
                ->with('success', 'Login berhasil! Selamat datang Admin.');
        }

        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard')
                ->with('success', 'Login berhasil! Selamat datang Dosen.');
        }

        // Default mahasiswa
        return redirect()->route('dashboard.me')
            ->with('success', 'Login berhasil! Selamat datang kembali.');
    }

    public function forgotpasswordpage()
    {
        return view('auth.forgot-password');
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // 1. Cek Otorisasi
        if ($currentUser->role === 'dosen') {
            if (
                $currentUser->id_jurusan != $user->id_jurusan ||
                $currentUser->id_angkatan != $user->id_angkatan ||
                $currentUser->id_keahlian != $user->id_keahlian
            ) {
                return redirect()->back()->with('error', 'Anda hanya dapat menyetujui mahasiswa dengan jurusan, angkatan, dan keahlian yang sama.');
            }
        } elseif ($currentUser->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // 2. Validasi Input (sesuaikan dengan nama yang dikirim dari form)
        $request->validate([
            'status_pengajuan' => 'required|in:Di Terima,Di Tolak',
            'keterangan_tolak' => 'required_if:status_pengajuan,Di Tolak|nullable|string|max:500',
        ]);

        try {
            $updateData = [
                'status_pengajuan' => $request->status_pengajuan,
            ];

            if ($request->status_pengajuan === 'Di Terima') {
                $updateData['is_active'] = true;
                $updateData['keterangan'] = null;           // Kosongkan di database
            } else {
                $updateData['is_active'] = false;
                $updateData['keterangan'] = $request->keterangan_tolak;   // Ambil dari form, simpan ke kolom 'keterangan'
            }

            // Update ke database
            $user->update($updateData);

            $statusText = $request->status_pengajuan === 'Di Terima' ? 'diterima' : 'ditolak';

            return redirect()->route(
                $currentUser->role === 'admin' ? 'admin.users.index' : 'dosen.users.index'
            )->with('success', "Status pengajuan mahasiswa {$user->name} berhasil {$statusText}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard.me')
            ->with('success', 'Anda telah logout.');
    }

    public function profile()
    {
        $user = Auth::user()->load([
            'jurusan',
            'keahlian',
            'angkatan',
            'keahlianTambahan' // Load relasi many-to-many
        ]);

        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        return view('auth.profile', compact('user', 'jurusans', 'keahlians', 'angkatans'));
    }

    private function addKeahlianTambahan($user, $id_keahlian)
    {
        // Cek sudah ada
        $exists = Keahlian_Tambahan::where('id_user', $user->id)
            ->where('id_keahlian', $id_keahlian)
            ->exists();

        if ($exists) {
            return 'Keahlian ini sudah ditambahkan sebelumnya';
        }

        // Cek bukan keahlian utama
        if ($user->id_keahlian == $id_keahlian) {
            return 'Tidak dapat menambahkan keahlian utama';
        }

        // Cek maksimal 3
        $currentCount = Keahlian_Tambahan::where('id_user', $user->id)->count();

        if ($currentCount >= 3) {
            return 'Maksimal 3 keahlian tambahan';
        }

        Keahlian_Tambahan::create([
            'id_user' => $user->id,
            'id_keahlian' => $id_keahlian,
            'is_active' => false,
            'status_pengajuan' => 'Sedang Di Ajukan',
            'keterangan' => null
        ]);

        return null;
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // -------------------------------
        // Quick upload foto saja
        // -------------------------------
        if ($request->hasFile('photo_profile') && count($request->all()) === 3) { // _token, _method, photo_profile
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $path = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
            $user->update(['photo_profile' => $path]);

            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        // -------------------------------
        // Validasi lengkap
        // -------------------------------
        $rules = [
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username,' . $user->id],
            'id_jurusan' => ['nullable', 'exists:jurusan,id_jurusan'],
            'id_keahlian' => ['nullable', 'exists:keahlian,id_keahlian'],
            'id_angkatan' => ['nullable', 'exists:angkatan,id'],
            'deskripsi' => ['nullable', 'string', 'max:1500'],
            'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan,Tidak ingin memberi tahu'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'background_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'id_keahlian_tambahan' => ['nullable', 'exists:keahlian,id_keahlian'],
            'video_url' => ['nullable', 'url'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers(), 'regex:/^\S*$/'];
        }

        $validated = $request->validate($rules);

        // Handle upload background
        if ($request->hasFile('background_url')) {
            if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
                Storage::disk('public')->delete($user->background_url);
            }
            $validated['background_url'] = ImageConversionService::storeWebp($request->file('background_url'), 'covers');
        }

        // Handle upload photo
        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        // Handle password
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->filled('id_keahlian_tambahan')) {

            $error = $this->addKeahlianTambahan($user, $request->id_keahlian_tambahan);

            if ($error) {
                return back()->with('error', $error);
            }
        }

        // Update data utama
        $user->update($validated);

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function storeKeahlianTambahan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_keahlian' => 'required|exists:keahlian,id_keahlian'
        ]);

        $error = $this->addKeahlianTambahan($user, $request->id_keahlian);

        if ($error) {
            return redirect()->back()->with('error', $error);
        }

        return redirect()->back()->with('success', 'Pengajuan keahlian berhasil dikirim');
    }
    /**
     * Remove the specified keahlian tambahan from storage.
     */
    public function destroyKeahlianTambahan(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID keahlian tambahan diperlukan'
            ], 400);
        }

        $user = Auth::user();

        try {
            $keahlianTambahan = Keahlian_Tambahan::where('id_user', $user->id)
                ->findOrFail($id);

            $keahlianTambahan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keahlian tambahan berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data keahlian tambahan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function keahliantambahanlist()
    {
        try {
            $user = Auth::user();

            // Ambil data keahlian tambahan dengan relasi keahlian
            $keahlianTambahan = Keahlian_Tambahan::where('id_user', $user->id)
                ->with('keahlian')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $keahlianTambahan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keahlian tambahan: ' . $e->getMessage()
            ], 500);
        }
    }
}