<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use App\Models\LearningCorner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Tampilkan form registrasi
    public function showRegister()
    {
        $jurusans  = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();
        
        return view('auth.register', compact('jurusans', 'keahlians', 'angkatans'));
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'email'          => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'username'       => ['required', 'string', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password'       => ['required', 'confirmed', Password::min(8)->mixedCase()],
            'id_jurusan'     => ['required', 'exists:jurusan,id_jurusan'],
            'id_keahlian'    => ['required', 'exists:keahlian,id_keahlian'],
            'id_angkatan'    => ['required', 'exists:angkatan,id'],
            'photo_profile'  => ['nullable', 'image', 'max:2048'],
            'photo_profile' => ['nullable','image','mimes:jpeg,png,jpg','max:2048'],
        ]);

        if ($request->hasFile('photo_profile')) {
            $path = $request->file('photo_profile')->store('photos', 'public');
            $validated['photo_profile'] = $path;
        }

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
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
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType  => $request->login,
            'password'  => $request->password,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.me'))
                ->with('success', 'Login berhasil!');
        }

        throw ValidationException::withMessages([
            'login' => ['Email/Username atau password salah.'],
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard.me')
            ->with('success', 'Anda telah logout.');
    }

    // Halaman profile + form edit inline
    public function profile()
    {
        $user = Auth::user()->load(['jurusan', 'keahlian']);
        $jurusans  = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatan   = Angkatan::all();

        return view('auth.profile', compact('user', 'jurusans', 'keahlians', 'angkatan'));
    }

    // Proses update profile
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    // ===== CEK JIKA HANYA UPDATE FOTO =====
    if ($request->hasFile('photo_profile') && $request->keys() == ['_token','_method','photo_profile']) {

        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        $path = $request->file('photo_profile')->store('photos', 'public');

        $user->update([
            'photo_profile' => $path
        ]);

        return back()->with('success','Foto berhasil diperbarui!');
    }

    // ===== VALIDASI NORMAL PROFILE =====
    $rules = [
        'nama_mahasiswa' => ['required','string','max:100'],
        'email' => ['nullable','email','max:100','unique:users,email,' . $user->id],
        'username' => ['required','string','max:100','regex:/^[a-zA-Z0-9_]+$/','unique:users,username,' . $user->id],
        'id_jurusan' => ['nullable','exists:jurusan,id_jurusan'],
        'id_keahlian' => ['nullable','exists:keahlian,id_keahlian'],
        'id_angkatan' => ['nullable','exists:angkatan,id'],
        'deskripsi' => ['nullable','string','max:1000'],
        'photo_profile' => ['nullable','image','mimes:jpeg,png,jpg','max:2048'],
        'background_url' => ['nullable','image','mimes:jpeg,png,jpg','max:4098'],
        'jenis_kelamin' => ['nullable','in:laki-laki,perempuan,tidak ingin memberi tahu'],    
    ];

    if ($request->filled('password')) {
        $rules['password'] = ['required','confirmed',Password::min(8)->mixedCase()];
    }

    $validated = $request->validate($rules);

    // ===== HANDLE BACKGROUND =====
    if ($request->hasFile('background_url')) {

        if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
            Storage::disk('public')->delete($user->background_url);
        }

        $validated['background_url'] = $request->file('background_url')
                                               ->store('covers','public');
    }

    if ($request->hasFile('photo_profile')) {
        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        $validated['photo_profile'] = $request->file('photo_profile')->store('photos','public');
    }

    if ($request->filled('password')) {
        $validated['password'] = Hash::make($request->password);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('profile')->with('success','Profil berhasil diperbarui!');
}
    public function isDosen()
{
    return $this->role === 'dosen';
}

public function isMahasiswa()
{
    return $this->role === 'mahasiswa';
}
}