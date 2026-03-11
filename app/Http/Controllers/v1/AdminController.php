<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Keahlian;
use App\Models\Jurusan;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    //For Pages User
    public function ListUser(Request $request)
{
    $users = User::whereIn('role', ['mahasiswa', 'admin', 'dosen'])
        ->where('id', '!=', Auth::id()) // tidak tampilkan user yang sedang login
        ->with(['jurusan', 'angkatan', 'keahlian'])
        ->withCount([
            'projects',
            'sertifikats',
            'learning_corners',
            
        ])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.daftar-mahasiswa', compact('users'));
}

    public function ViewAddUser(){
        $jurusan  = Jurusan::all();
        $keahlian = Keahlian::all();
        $angkatan = Angkatan::all();
        return view('admin.user.views_add_user',compact('jurusan', 'keahlian', 'angkatan'));
    }

    public function AddUser(Request $request)
{
    $validated = $request->validate([
        'nama_mahasiswa' => ['required', 'string', 'max:100'],
        'email' => ['nullable','email','max:100','unique:users,email'],
        'username' => ['required','string','max:100','unique:users,username','regex:/^[a-zA-Z0-9_]+$/'
        ],

        'password' => [
            'required',
            'confirmed',
            Password::min(8)->mixedCase()
        ],

        'role' => [
            'required',
            'in:mahasiswa,dosen,admin'
        ],

        'id_jurusan' => [
            'nullable',
            'required_if:role,mahasiswa,dosen',
            'exists:jurusan,id_jurusan'
        ],

        'id_keahlian' => [
            'nullable',
            'required_if:role,mahasiswa,dosen',
            'exists:keahlian,id_keahlian'
        ],

        'id_angkatan' => [
            'nullable',
            'required_if:role,mahasiswa,dosen',
            'exists:angkatan,id'
        ],

        'photo_profile' => [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048'
        ],
    ]);


    $photoPath = null;

    if ($request->hasFile('photo_profile')) {
        $photoPath = $request->file('photo_profile')
            ->store('photo_profile', 'public');
    }

    User::create([
        'nama_mahasiswa' => $validated['nama_mahasiswa'],
        'email' => $validated['email'] ?? null,
        'username' => $validated['username'],
        'password' => Hash::make($validated['password']),

        'photo_profile' => $photoPath,

        'role' => $validated['role'],

        'id_jurusan' => $validated['id_jurusan'] ?? null,
        'id_keahlian' => $validated['id_keahlian'] ?? null,
        'id_angkatan' => $validated['id_angkatan'] ?? null,

        'status_pengajuan' => 'Di Terima',
        'is_active' => 1,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil ditambahkan.');
    }

    public function DetailsUser($id)
{
     $jurusans = Jurusan::all();
    $keahlians = Keahlian::all();
    $angkatans = Angkatan::all();
    $user = User::with(['jurusan','keahlian','angkatan'])->findOrFail($id);
    return view('admin.user.views_edit_user', compact(
        'user', 
        'jurusans', 
        'keahlians', 
        'angkatans'
    ));
}

public function UpdateUser(Request $request, $id_user)
{
    $user = User::findOrFail($id_user);

    // Base validation rules
    $rules = [
        'nama_mahasiswa' => ['sometimes','string','max:100'],

        'username' => [
            'sometimes',
            'string',
            'max:100',
            'regex:/^[a-zA-Z0-9_]+$/',
            Rule::unique('users')->ignore($user->id)
        ],

        'email' => [
            'sometimes',
            'nullable',
            'email',
            'max:100',
            Rule::unique('users')->ignore($user->id)
        ],

        'role' => [
            'sometimes',
            'in:mahasiswa,dosen,admin'
        ],

        'status_pengajuan' => [
            'sometimes',
            'in:Di Terima,Di Tolak'
        ],

        'is_active' => [
            'sometimes',
            'boolean'
        ],

        'id_jurusan' => [
            'sometimes',
            'nullable',
            'exists:jurusan,id_jurusan'
        ],

        'id_keahlian' => [
            'sometimes',
            'nullable',
            'exists:keahlian,id_keahlian'
        ],

        'id_angkatan' => [
            'sometimes',
            'nullable',
            'exists:angkatan,id'
        ],

        'password' => [
            'nullable',
            'string',
            'confirmed',
            Password::min(8)->mixedCase()
        ],

        'photo_profile' => [
            'sometimes',
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048'
        ],
    ];

    $validated = $request->validate($rules);

    $updateData = [];

    // Update basic fields
    foreach ([
        'nama_mahasiswa',
        'username',
        'email',
        'role',
        'status_pengajuan',
        'is_active',
        'id_jurusan',
        'id_keahlian',
        'id_angkatan'
    ] as $field) {
        if ($request->has($field)) {
            $updateData[$field] = $validated[$field] ?? null;
        }
    }

    // Password update
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    // Role logic
    if ($request->has('role') && $validated['role'] === 'admin') {
        $updateData['id_jurusan'] = null;
        $updateData['id_keahlian'] = null;
        $updateData['id_angkatan'] = null;
    }

    // Photo upload
    if ($request->hasFile('photo_profile')) {

        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        $photoPath = $request->file('photo_profile')->store('photo_profile', 'public');

        $updateData['photo_profile'] = $photoPath;
    }

    // Update user
    $user->update($updateData);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil diperbarui.');
}
    public function destroy(User $user){
         $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

      public function projects()
    {
        $projects = Project::with(['mahasiswa', 'leader'])->latest()->paginate(12);
        return view('admin.project', compact('projects'));
    }

    public function sertifikat(){
        return view('admin.sertifikat');
    }
}