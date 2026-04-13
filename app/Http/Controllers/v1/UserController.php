<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use App\Models\LearningCorner;
use App\Models\Project;
use App\Models\Keahlian_Tambahan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\ImageConversionService;

class UserController extends Controller
{
    // Tampilkan form registrasi
    public function showRegister()
    {
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        return view('auth.register', compact('jurusans', 'keahlians', 'angkatans'));
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $sessionId = $request->session()->getId();

        $guestIdentifier = md5($ipAddress . $userAgent . $sessionId);

        $registrationCount = Cache::remember("registration_count_{$guestIdentifier}", 3600, function () {
            return 0;
        });

        // Cek di session juga sebagai backup
        $sessionCount = $request->session()->get('registration_attempts', 0);

        $totalAttempts = max($registrationCount, $sessionCount);

        if ($totalAttempts >= 3) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda telah mencapai batas maksimal 3 kali pengajuan registrasi. Silakan hubungi admin untuk bantuan lebih lanjut.');
        }

        $validated = $request->validate([
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()],
            'id_jurusan' => ['required', 'exists:jurusan,id_jurusan'],
            'id_keahlian' => ['required', 'exists:keahlian,id_keahlian'],
            'id_angkatan' => ['required', 'exists:angkatan,id'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);

        $validated['role'] = 'mahasiswa';

        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['status_pengajuan'] = 'Sedang Di Ajukan';

        User::create($validated);

        $newCount = $totalAttempts + 1;

        Cache::put("registration_count_{$guestIdentifier}", $newCount, now()->addHours(24));

        // Simpan di session
        $request->session()->put('registration_attempts', $newCount);
        $request->session()->put('last_registration_time', now());

        return redirect()->route('login')
            ->with('success', 'Pengajuan telah berhasil dibuat, silahkan tunggu admin/dosen angkatan anda menyetujui.');
    }
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login (email ATAU username)
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $request->login)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Akun tidak ditemukan.',
            ])->onlyInput('login');
        }

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

        $credentials = [
            $fieldType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();


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

        return back()->withErrors([
            'login' => 'Password yang Anda masukkan salah.',
        ])->onlyInput('login');
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
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
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
    public function destroyKeahlianTambahan($id)
    {
        $user = Auth::user();

        try {
            $keahlianTambahan = Keahlian_Tambahan::where('id_user', $user->id)
                ->findOrFail($id);

            $keahlianTambahan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keahlian tambahan berhasil dihapus'
            ]);

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