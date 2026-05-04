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
    public function shownotCompleteRegistration()
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

        $jurusans  = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        $jurusanName  = $user->jurusan  ? $user->jurusan->nama_jurusan   : '-';
        $keahlianName = $user->keahlian ? $user->keahlian->nama_keahlian : '-';
        $angkatanName = $user->angkatan ? $user->angkatan->nama_angkatan : '-';

        return view('auth.complete-registration', compact('tempUserData', 'user', 'jurusans', 'keahlians', 'angkatans', 'jurusanName', 'keahlianName', 'angkatanName'));
    }

    // Proses register (normal flow)
    public function notcompleteregister(Request $request)
    {
        $isCompleting = $request->has('completing_registration') && $request->completing_registration == 'true';

        if ($isCompleting) {
            $userId = $request->session()->get('temp_user_id');
            $user   = User::find($userId);

            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Sesi tidak valid. Silakan login kembali.');
            }

            $validated = $request->validate([
                'username'         => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/'],
                'password'         => ['required', 'confirmed', Password::min(8)->mixedCase(), 'regex:/^\S*$/'],
                'email'            => ['nullable', 'email', 'max:100', 'unique:users,email'],
                'description'      => ['nullable', 'string', 'max:500'],
                'video_url'        => ['nullable', 'url', 'max:255'],
                'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
                'photo_profile'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5012'],
            ]);

            $backgroundPath  = null;
            $photoProfilePath = null;

            if ($request->hasFile('background_image')) {
                $backgroundPath = ImageConversionService::storeWebp($request->file('background_image'), 'backgrounds');
            }

            if ($request->hasFile('photo_profile')) {
                $photoProfilePath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
            }

            $user->update([
                'username'       => $validated['username'],
                'email'          => $validated['email'] ?? $user->email,
                'password'       => Hash::make($validated['password']),
                'deskripsi'      => $validated['description'] ?? null,
                'video_url'      => $validated['video_url'] ?? null,
                'background_url' => $backgroundPath,
                'photo_profile'  => $photoProfilePath,
            ]);

            $this->sendNotifications($user);

            $request->session()->forget(['temp_user_id', 'temp_user_data', 'incomplete_registration_data']);

            return redirect()->route('login')
                ->with('success', 'Data berhasil dilengkapi! Silakan Login Dengan Password Yang Sudah Anda Buat');
        }

        // Normal registration flow
        $validated = $request->validate([
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'nim'            => ['required', 'string', 'max:50', 'unique:users,nim'],
            'tanggal_lahir'  => ['required', 'date', 'before_or_equal:today'],
            'email'          => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'id_jurusan'     => ['required', 'exists:jurusan,id_jurusan'],
            'id_keahlian'    => ['required', 'exists:keahlian,id_keahlian'],
            'id_angkatan'    => ['required', 'exists:angkatan,id'],
            'photo_profile'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $validated['role']      = 'mahasiswa';
        $validated['is_active'] = true;
        $validated['password']  = null;

        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        $user = User::create($validated);
        $user->load('jurusan', 'angkatan', 'keahlian');

        $this->sendNotifications($user);

        return redirect()->route('login')
            ->with('success', 'Pengajuan telah berhasil dibuat, silahkan tunggu admin/dosen angkatan anda menyetujui.');
    }

    // Tampilkan form registrasi
       public function showRegister()
    {
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        return view('auth.register', compact('jurusans', 'keahlians', 'angkatans'));
    }

    public function register(Request $request)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $sessionId = $request->session()->getId();

        $guestIdentifier = md5($ipAddress . $userAgent . $sessionId);

        $registrationCount = Cache::remember("registration_count_{$guestIdentifier}", 3600, function () {
            return 0;
        });

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
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase(), 'regex:/^\S*$/'],
            'id_jurusan' => ['required', 'exists:jurusan,id_jurusan'],
            'id_keahlian' => ['required', 'exists:keahlian,id_keahlian'],
            'id_angkatan' => ['required', 'exists:angkatan,id'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'nim' => ['required', 'string', 'max:50', 'unique:users,nim'],
            'tanggal_lahir' => ['required', 'date']
        ]);

        $validated['role'] = 'mahasiswa';

        if ($request->hasFile('photo_profile')) {
            $validated['photo_profile'] = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['status_pengajuan'] = 'Sedang Di Ajukan';

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
        $jurusanNama  = $user->jurusan->nama_jurusan   ?? '-';
        $angkatanNama = $user->angkatan->nama_angkatan ?? '-';

        NotificationController::add(
            'user-registered',
            [
                'title'     => 'Mahasiswa Menyelesaikan Pendaftaran',
                'message'   => "Mahasiswa telah menyelesaikan pendaftarannya: {$user->nama_mahasiswa} ({$jurusanNama} - {$angkatanNama})",
                'user_id'   => $user->id,
                'user_name' => $user->nama_mahasiswa,
                'link'      => url(app()->getLocale() . '/admin/manageUser'),
            ],
            'high'
        );
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $fieldType = 'email';
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } elseif (preg_match('/^\d+$/', $request->login)) {
            $fieldType = 'nim';
        } else {
            $fieldType = 'username';
        }

        $user = User::where($fieldType, $request->login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Akun tidak ditemukan.'])->onlyInput('login');
        }

        if ($user->is_active == 0 && empty($user->status_pengajuan)) {
            return back()->withErrors(['login' => 'AKUN_DIBLOKIR'])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Sedang Di Ajukan') {
            return back()->withErrors(['login' => 'PENGAJUAN_DIPROSES'])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Di Tolak') {
            return back()->withErrors(['login' => 'PENGAJUAN_DITOLAK'])->onlyInput('login');
        }

        if ($user->status_pengajuan === 'Di Terima' && $user->is_active == 0) {
            return back()->withErrors(['login' => 'AKUN_DIBLOKIR'])->onlyInput('login');
        }

        $passwordValid = false;

        if (empty($user->password)) {
            $tempPassword = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null;

            if ($tempPassword && $request->password === $tempPassword) {
                $passwordValid = true;

                $userData = [
                    'id'             => $user->id,
                    'nama_mahasiswa' => $user->nama_mahasiswa,
                    'nim'            => $user->nim,
                    'tanggal_lahir'  => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null,
                    'email'          => $user->email,
                    'id_jurusan'     => $user->id_jurusan,
                    'id_keahlian'    => $user->id_keahlian,
                    'id_angkatan'    => $user->id_angkatan,
                    'photo_profile'  => $user->photo_profile,
                ];

                session([
                    'temp_user_id'               => $user->id,
                    'temp_user_data'             => $userData,
                    'incomplete_registration_data' => $userData,
                ]);

                return redirect()->route('register.complete')
                    ->with('warning', 'Silakan lengkapi data akun Anda (username, password, dll) untuk melanjutkan.');
            }
        } else {
            if (Hash::check($request->password, $user->password)) {
                $passwordValid = true;
            }
        }

        if (!$passwordValid) {
            return back()->withErrors(['login' => 'Password yang Anda masukkan salah.'])->onlyInput('login');
        }

        $hasPasskey = $user->passkeys()->count() > 0;

        if ($hasPasskey) {
            session(['2fa_user_id'             => $user->id]);
            session(['2fa_requires_verification' => true]);
            session(['2fa_remember'             => $request->boolean('remember')]);

            return redirect()->route('2fa.verify');
        }

        Auth::login($user, $request->boolean('remember'));
        session(['user_role' => $user->role]);
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.index')->with('success', 'Login berhasil! Selamat datang Admin.');
        }

        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard')->with('success', 'Login berhasil! Selamat datang Dosen.');
        }

        return redirect()->route('dashboard.me')->with('success', 'Login berhasil! Selamat datang kembali.');
    }

    public function forgotpasswordpage()
    {
        return view('auth.forgot-password');
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user        = User::findOrFail($id);

        if ($currentUser->role === 'dosen') {
            if (
                $currentUser->id_jurusan  != $user->id_jurusan  ||
                $currentUser->id_angkatan != $user->id_angkatan ||
                $currentUser->id_keahlian != $user->id_keahlian
            ) {
                return redirect()->back()->with('error', 'Anda hanya dapat menyetujui mahasiswa dengan jurusan, angkatan, dan keahlian yang sama.');
            }
        } elseif ($currentUser->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'status_pengajuan'  => 'required|in:Di Terima,Di Tolak',
            'keterangan_tolak'  => 'required_if:status_pengajuan,Di Tolak|nullable|string|max:500',
        ]);

        try {
            $updateData = ['status_pengajuan' => $request->status_pengajuan];

            if ($request->status_pengajuan === 'Di Terima') {
                $updateData['is_active']  = true;
                $updateData['keterangan'] = null;
            } else {
                $updateData['is_active']  = false;
                $updateData['keterangan'] = $request->keterangan_tolak;
            }

            $user->update($updateData);

            $statusText = $request->status_pengajuan === 'Di Terima' ? 'diterima' : 'ditolak';

            return redirect()->route(
                $currentUser->role === 'admin' ? 'admin.users.index' : 'dosen.users.index'
            )->with('success', "Status pengajuan mahasiswa {$user->name} berhasil {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard.me')->with('success', 'Anda telah logout.');
    }

    public function profile()
    {
        $user = Auth::user()->load([
            'jurusan',
            'keahlian',
            'angkatan',
            'keahlianTambahan',
        ]);

        $jurusans  = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        return view('auth.profile', compact('user', 'jurusans', 'keahlians', 'angkatans'));
    }

    private function addKeahlianTambahan($user, $id_keahlian)
    {
        $exists = Keahlian_Tambahan::where('id_user', $user->id)
            ->where('id_keahlian', $id_keahlian)
            ->exists();

        if ($exists) {
            return 'Keahlian ini sudah ditambahkan sebelumnya';
        }

        if ($user->id_keahlian == $id_keahlian) {
            return 'Tidak dapat menambahkan keahlian utama';
        }

        $currentCount = Keahlian_Tambahan::where('id_user', $user->id)->count();

        if ($currentCount >= 3) {
            return 'Maksimal 3 keahlian tambahan';
        }

        Keahlian_Tambahan::create([
            'id_user'          => $user->id,
            'id_keahlian'      => $id_keahlian,
            'is_active'        => false,
            'status_pengajuan' => 'Sedang Di Ajukan',
            'keterangan'       => null,
        ]);

        return null;
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Quick upload foto saja
    if ($request->hasFile('photo_profile') && count($request->all()) === 3) {
        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        $path = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        $user->update(['photo_profile' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    $rules = [
        'nama_mahasiswa'      => ['required', 'string', 'max:100'],
        'email'               => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
        'username'            => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username,' . $user->id],
        'id_jurusan'          => ['nullable', 'exists:jurusan,id_jurusan'],
        'id_keahlian'         => ['nullable', 'exists:keahlian,id_keahlian'],
        'id_angkatan'         => ['nullable', 'exists:angkatan,id'],
        'deskripsi'           => ['nullable', 'string', 'max:1500'],
        'jenis_kelamin'       => ['nullable', 'in:Laki-laki,Perempuan,Tidak ingin memberi tahu'],
        'photo_profile'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        'background_url'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        'id_keahlian_tambahan'=> ['nullable', 'exists:keahlian,id_keahlian'],
        'video_url'           => ['nullable', 'url'],
        'tanggal_lahir'       => ['nullable', 'date', 'before_or_equal:today', 'after:1900-01-01'], // Tambahkan validasi tanggal lahir
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

    // Handle tanggal_lahir - format ke Y-m-d jika ada
    if ($request->filled('tanggal_lahir')) {
        $validated['tanggal_lahir'] = Carbon::parse($request->tanggal_lahir)->format('Y-m-d');
    } else {
        // Jika tidak diisi, set ke null (opsional, tergantung kebutuhan)
        $validated['tanggal_lahir'] = null;
    }

    // Handle keahlian tambahan
    if ($request->filled('id_keahlian_tambahan')) {
        $error = $this->addKeahlianTambahan($user, $request->id_keahlian_tambahan);
        if ($error) {
            return back()->with('error', $error);
        }
    }

    // Hapus nim dari validated array jika ada (karena NIM tidak boleh diupdate)
    unset($validated['nim']);
    
    // Hapus field yang tidak boleh diupdate atau tidak ada di tabel users
    // id_keahlian_tambahan sudah ditangani terpisah, jadi hapus dari validated
    unset($validated['id_keahlian_tambahan']);

    $user->update($validated);

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
}

    // =========================================================
    // PENGALAMAN KERJA
    // =========================================================

    /**
     * Tambah pengalaman kerja baru
     */
    public function storePengalamanKerja(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_pt'               => 'required|string|max:200',
            'bagian_kerja'          => 'required|string|max:200',
            'tahun_mulai'           => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'tahun_akhir'           => 'nullable|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'masih_bekerja'         => 'nullable|boolean',
            'sertifikat_pendukung'  => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $sertifikatPath = null;
        if ($request->hasFile('sertifikat_pendukung')) {
            $sertifikatPath = $request->file('sertifikat_pendukung')->store('sertifikat', 'public');
        }

        $pengalamanLama = $user->pengalaman_kerja ?? [];
        $pengalamanBaru = [
            'id'                    => uniqid(),
            'nama_pt'               => $request->nama_pt,
            'bagian_kerja'          => $request->bagian_kerja,
            'tahun_mulai'           => $request->tahun_mulai,
            'tahun_akhir'           => $request->boolean('masih_bekerja') ? null : $request->tahun_akhir,
            'masih_bekerja'         => $request->boolean('masih_bekerja'),
            'sertifikat_pendukung'  => $sertifikatPath,
        ];

        $pengalamanLama[] = $pengalamanBaru;

        $user->update(['pengalaman_kerja' => $pengalamanLama]);

        return redirect()->back()->with('success', 'Pengalaman kerja berhasil ditambahkan!');
    }

    /**
     * Hapus pengalaman kerja berdasarkan ID
     */
    public function destroyPengalamanKerja(Request $request)
    {
        $id   = $request->query('id');
        $user = Auth::user();

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID diperlukan'], 400);
        }

        $pengalamanList = $user->pengalaman_kerja ?? [];
        $found          = false;

        foreach ($pengalamanList as $key => $item) {
            if ($item['id'] === $id) {
                // Hapus file sertifikat pendukung jika ada
                if (!empty($item['sertifikat_pendukung']) && Storage::disk('public')->exists($item['sertifikat_pendukung'])) {
                    Storage::disk('public')->delete($item['sertifikat_pendukung']);
                }
                unset($pengalamanList[$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $user->update(['pengalaman_kerja' => array_values($pengalamanList)]);

        return response()->json(['success' => true, 'message' => 'Pengalaman kerja berhasil dihapus']);
    }

    // =========================================================
    // PENDIDIKAN
    // =========================================================

    /**
     * Tambah pendidikan baru
     */
    public function storePendidikan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_sekolah' => 'required|string|max:300',
            'jenjang'      => 'required|in:SD,SMP,SMA/SMK,D1,D2,D3,D4,S1,S2,S3,Kursus/Pelatihan',
            'jurusan_sek'  => 'nullable|string|max:200',
            'tahun_masuk'  => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'tahun_lulus'  => 'nullable|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'masih_kuliah' => 'nullable|boolean',
        ]);

        $pendidikanLama = $user->pendidikan ?? [];
        $pendidikanBaru = [
            'id'           => uniqid(),
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang'      => $request->jenjang,
            'jurusan_sek'  => $request->jurusan_sek,
            'tahun_masuk'  => $request->tahun_masuk,
            'tahun_lulus'  => $request->boolean('masih_kuliah') ? null : $request->tahun_lulus,
            'masih_kuliah' => $request->boolean('masih_kuliah'),
        ];

        $pendidikanLama[] = $pendidikanBaru;

        // Urutkan dari tahun masuk terbaru
        usort($pendidikanLama, fn($a, $b) => ($b['tahun_masuk'] ?? 0) - ($a['tahun_masuk'] ?? 0));

        $user->update(['pendidikan' => $pendidikanLama]);

        return redirect()->back()->with('success', 'Data pendidikan berhasil ditambahkan!');
    }

    /**
     * Hapus pendidikan berdasarkan ID
     */
    public function destroyPendidikan(Request $request)
    {
        $id   = $request->query('id');
        $user = Auth::user();

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID diperlukan'], 400);
        }

        $pendidikanList = $user->pendidikan ?? [];
        $found          = false;

        foreach ($pendidikanList as $key => $item) {
            if ($item['id'] === $id) {
                unset($pendidikanList[$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $user->update(['pendidikan' => array_values($pendidikanList)]);

        return response()->json(['success' => true, 'message' => 'Data pendidikan berhasil dihapus']);
    }

    // =========================================================
    // KEAHLIAN TAMBAHAN (existing methods)
    // =========================================================

    public function storeKeahlianTambahan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
        ]);

        $error = $this->addKeahlianTambahan($user, $request->id_keahlian);

        if ($error) {
            return redirect()->back()->with('error', $error);
        }

        return redirect()->back()->with('success', 'Pengajuan keahlian berhasil dikirim');
    }

    public function destroyKeahlianTambahan(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID keahlian tambahan diperlukan'], 400);
        }

        $user = Auth::user();

        try {
            $keahlianTambahan = Keahlian_Tambahan::where('id_user', $user->id)->findOrFail($id);
            $keahlianTambahan->delete();

            return response()->json(['success' => true, 'message' => 'Keahlian tambahan berhasil dihapus']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Data keahlian tambahan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function keahliantambahanlist()
    {
        try {
            $user = Auth::user();

            $keahlianTambahan = Keahlian_Tambahan::where('id_user', $user->id)
                ->with('keahlian')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $keahlianTambahan]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data keahlian tambahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API endpoint: search nama sekolah dari seluruh Indonesia
     * Menggunakan data statis yang komprehensif (atau bisa diganti ke API eksternal)
     */
    public function searchSekolah(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Data sekolah dari API SIAP/Kemendikbud dapat diintegrasikan di sini.
        // Untuk sementara menggunakan sample data yang bisa dikembangkan.
        // Anda bisa mengganti ini dengan request ke:
        // https://api-sekolah-indonesia.vercel.app/sekolah?s={query}&p=1&l=10
        // atau https://sekolah.data.kemdikbud.go.id/index.php/Csekolah/

        try {
            // Coba gunakan API eksternal Sekolah Indonesia
            $client   = new \Illuminate\Http\Client\Factory();
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->get('https://api-sekolah-indonesia.vercel.app/sekolah', [
                    's' => $query,
                    'p' => 1,
                    'l' => 10,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                $sekolahList = collect($data['dataSekolah'] ?? [])
                    ->take(10)
                    ->map(fn($s) => [
                        'nama'      => $s['sekolah'] ?? $s['nama'] ?? '',
                        'kota'      => $s['kabkota']  ?? $s['kota'] ?? '',
                        'provinsi'  => $s['propinsi']  ?? $s['provinsi'] ?? '',
                        'jenjang'   => $s['bentuk']    ?? '',
                    ])
                    ->filter(fn($s) => !empty($s['nama']))
                    ->values();

                return response()->json($sekolahList);
            }
        } catch (\Exception $e) {
            // Fallback ke data statis jika API gagal
        }

        // Fallback data statis universitas dan sekolah ternama Indonesia
        $sekolahStatis = [
            ['nama' => 'Universitas Indonesia', 'kota' => 'Depok', 'provinsi' => 'Jawa Barat', 'jenjang' => 'Universitas'],
            ['nama' => 'Institut Teknologi Bandung', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'jenjang' => 'Institut'],
            ['nama' => 'Universitas Gadjah Mada', 'kota' => 'Yogyakarta', 'provinsi' => 'DIY', 'jenjang' => 'Universitas'],
            ['nama' => 'Institut Teknologi Sepuluh Nopember', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'jenjang' => 'Institut'],
            ['nama' => 'Universitas Diponegoro', 'kota' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'jenjang' => 'Universitas'],
            ['nama' => 'Universitas Brawijaya', 'kota' => 'Malang', 'provinsi' => 'Jawa Timur', 'jenjang' => 'Universitas'],
            ['nama' => 'Universitas Padjadjaran', 'kota' => 'Sumedang', 'provinsi' => 'Jawa Barat', 'jenjang' => 'Universitas'],
            ['nama' => 'Universitas Airlangga', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'jenjang' => 'Universitas'],
            ['nama' => 'Universitas Bina Nusantara', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'jenjang' => 'Universitas'],
            ['nama' => 'Universitas Telkom', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'jenjang' => 'Universitas'],
            ['nama' => 'SMAN 1 Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'jenjang' => 'SMA'],
            ['nama' => 'SMAN 3 Bandung', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'jenjang' => 'SMA'],
            ['nama' => 'SMKN 1 Bandung', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'jenjang' => 'SMK'],
            ['nama' => 'Politeknik Negeri Bandung', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'jenjang' => 'Politeknik'],
            ['nama' => 'Politeknik Elektronika Negeri Surabaya', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'jenjang' => 'Politeknik'],
        ];

        $queryLower = strtolower($query);
        $filtered   = array_filter($sekolahStatis, fn($s) => str_contains(strtolower($s['nama']), $queryLower));

        return response()->json(array_values($filtered));
    }

    public function detailPengalamanKerja(Request $request)
{
    $id = $request->query('id');
    $user = Auth::user();
    $list = $user->pengalaman_kerja ?? [];
    $item = collect($list)->firstWhere('id', $id);
    if (!$item) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    return response()->json(['success' => true, 'data' => $item]);
}

public function updatePengalamanKerja(Request $request)
{
    $id   = $request->query('id');
    $user = Auth::user();

    $request->validate([
        'nama_pt'       => 'required|string|max:200',
        'bagian_kerja'  => 'required|string|max:200',
        'tahun_mulai'   => 'required|digits:4|integer|min:1950|max:'.(date('Y')+1),
        'tahun_akhir'   => 'nullable|digits:4|integer|min:1950|max:'.(date('Y')+1),
        'masih_bekerja' => 'nullable|boolean',
    ]);

    $list  = $user->pengalaman_kerja ?? [];
    $found = false;

    foreach ($list as &$entry) {   // ✅ pakai &
        if ($entry['id'] === $id) {
            $entry['nama_pt']       = $request->nama_pt;
            $entry['bagian_kerja']  = $request->bagian_kerja;
            $entry['tahun_mulai']   = $request->tahun_mulai;
            $entry['masih_bekerja'] = $request->boolean('masih_bekerja');
            $entry['tahun_akhir']   = $request->boolean('masih_bekerja') ? null : $request->tahun_akhir;
            $found = true;
            break;
        }
    }
    unset($entry); // ✅ wajib

    if (!$found) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    $user->update(['pengalaman_kerja' => $list]);
    return response()->json(['success' => true, 'message' => 'Pengalaman kerja berhasil diperbarui']);
}

public function detailPendidikan(Request $request)
{
    $id = $request->query('id');
    $user = Auth::user();
    $list = $user->pendidikan ?? [];
    $item = collect($list)->firstWhere('id', $id);
    if (!$item) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    return response()->json(['success' => true, 'data' => $item]);
}

public function updatePendidikan(Request $request)
{
    $id   = $request->query('id');
    $user = Auth::user();

    $request->validate([
        'nama_sekolah' => 'required|string|max:300',
        'jenjang'      => 'required|in:SD,SMP,SMA/SMK,D1,D2,D3,D4,S1,S2,S3,Kursus/Pelatihan',
        'jurusan_sek'  => 'nullable|string|max:200',
        'tahun_masuk'  => 'required|digits:4|integer|min:1950|max:'.(date('Y')+1),
        'tahun_lulus'  => 'nullable|digits:4|integer|min:1950|max:'.(date('Y')+1),
        'masih_kuliah' => 'nullable|boolean',
    ]);

    $list  = $user->pendidikan ?? [];
    $found = false;

    foreach ($list as &$entry) {   // ✅ pakai & (reference) dan nama berbeda
        if ($entry['id'] === $id) {
            $entry['nama_sekolah'] = $request->nama_sekolah;
            $entry['jenjang']      = $request->jenjang;
            $entry['jurusan_sek']  = $request->jurusan_sek;
            $entry['tahun_masuk']  = $request->tahun_masuk;
            $entry['masih_kuliah'] = $request->boolean('masih_kuliah');
            $entry['tahun_lulus']  = $request->boolean('masih_kuliah') ? null : $request->tahun_lulus;
            $found = true;
            break;
        }
    }
    unset($entry); // ✅ wajib lepas reference

    if (!$found) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    $user->update(['pendidikan' => $list]);
    return response()->json(['success' => true, 'message' => 'Data pendidikan berhasil diperbarui']);
}


}