<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Keahlian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Tampilkan form registrasi
    public function showRegister()
    {
        $jurusans  = \App\Models\Jurusan::all();
        $keahlians = \App\Models\Keahlian::all();

        return view('auth.register', compact('jurusans', 'keahlians'));
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
            'photo_profile'  => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        if ($request->hasFile('photo_profile')) {
            $path = $request->file('photo_profile')->store('photos', 'public');
            $validated['photo_profile'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);
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
            $fieldType => $request->login,
            'password' => $request->password,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
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

        return redirect()->route('login')
            ->with('success', 'Anda telah logout.');
    }

    // Halaman profile (contoh sederhana)
    public function profile()
    {
        $user = Auth::user()->load(['jurusan', 'keahlian']);

        return view('auth.profile', compact('user'));
    }
}
