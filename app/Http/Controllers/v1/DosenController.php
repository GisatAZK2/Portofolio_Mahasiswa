<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Sertifikat;
use App\Models\Keahlian;
use App\Models\Jurusan;
use App\Models\Angkatan;
use App\Models\LearningCorner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DosenController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'dosen') {
            abort(Response::HTTP_FORBIDDEN, 'Akses hanya untuk dosenistrator.');
        }
    }

    
    private function createProjectTasks(Project $project, array $tasks)
{
    // Reload semua relasi biar fresh (IMPORTANT)
    $project = $project->fresh(['members']);

    // Kumpulkan semua user ID yang diizinkan
    $allowedUserIds = collect();

    // Owner
    if (!empty($project->id_mahasiswa)) {
        $allowedUserIds->push((int) $project->id_mahasiswa);
    }

    // Leader
    if (!empty($project->leader_id)) {
        $allowedUserIds->push((int) $project->leader_id);
    }

    // Members
    $allowedUserIds = $allowedUserIds
        ->merge(
            $project->members->pluck('id')->map(fn($id) => (int) $id)
        )
        ->filter()
        ->unique()
        ->values()
        ->all();

    $savedTaskIds = [];

    foreach ($tasks as $task) {

        if (!isset($task['user_id'], $task['name_task'])) {
            continue;
        }

        $taskId     = isset($task['id']) ? (int) $task['id'] : null;
        $taskUserId = (int) $task['user_id'];
        $taskName   = trim($task['name_task']);

        if ($taskUserId === 0 || $taskName === '') {
            continue;
        }

        // 🔥 DEBUG OPTIONAL (kalau mau cek)
        // Log::info('Checking task user', [
        //     'task_user_id' => $taskUserId,
        //     'allowed' => $allowedUserIds
        // ]);

        if (!in_array($taskUserId, $allowedUserIds, true)) {
            Log::warning('Task user not allowed for project', [
                'project_id' => $project->id,
                'task_user_id' => $taskUserId,
                'allowed_users' => $allowedUserIds
            ]);
            continue;
        }

        // UPDATE
        if ($taskId) {
            $existingTask = ProjectTask::where('project_id', $project->id)
                ->where('id', $taskId)
                ->first();

            if ($existingTask) {
                $existingTask->update([
                    'user_id'   => $taskUserId,
                    'name_task' => $taskName,
                ]);

                $savedTaskIds[] = $existingTask->id;
                continue;
            }
        }

        // CREATE
        $newTask = ProjectTask::create([
            'project_id' => $project->id,
            'user_id'    => $taskUserId,
            'name_task'  => $taskName,
            'is_done'    => false,
        ]);

        $savedTaskIds[] = $newTask->id;
    }

    return $savedTaskIds;
}

    private function getdosenFilterScope($query)
    {
        $dosen = Auth::user();
        
        // Hanya mahasiswa yang disetujui (status_pengajuan Di Terima), plus filter dosen jika ada
        $query->where('status_pengajuan', 'Di Terima');

        if ($dosen->id_jurusan || $dosen->id_angkatan || $dosen->id_keahlian) {
            $query->where(function($q) use ($dosen) {
                if ($dosen->id_jurusan) {
                    $q->where('id_jurusan', $dosen->id_jurusan);
                }
                if ($dosen->id_angkatan) {
                    $q->where('id_angkatan', $dosen->id_angkatan);
                }
                if ($dosen->id_keahlian) {
                    $q->where('id_keahlian', $dosen->id_keahlian);
                }
            });
        }
        
        return $query;
    }

    private function getdosenFilterScopeForRelated($query, $relation)
    {
        $dosen = Auth::user();
        
        // Hanya mahasiswa yang disetujui (status_pengajuan Di Terima), plus filter dosen jika ada
        $query->whereHas($relation, function($q) use ($dosen) {
            $q->where('status_pengajuan', 'Di Terima');

            if ($dosen->id_jurusan) {
                $q->where('id_jurusan', $dosen->id_jurusan);
            }
            if ($dosen->id_angkatan) {
                $q->where('id_angkatan', $dosen->id_angkatan);
            }
            if ($dosen->id_keahlian) {
                $q->where('id_keahlian', $dosen->id_keahlian);
            }
        });
        
        return $query;
    }

    public function index() {
        $this->authorizeAccess();
        return view('dosen.index');
    }

    //For Pages User
    public function ListUser(Request $request){
        $this->authorizeAccess();
        $users = User::whereIn('role', ['mahasiswa'])
            ->where('id', '!=', Auth::id()) 
            ->with(['jurusan', 'angkatan', 'keahlian'])
            ->withCount([
                'projects',
                'sertifikats',
                'learning_corners'
            ])
            ->orderBy('created_at', 'desc');
            
        // Apply filter based on dosen's own attributes
        $users = $this->getdosenFilterScope($users)->get();

        return view('dosen.daftar-mahasiswa', compact('users'));
    }

    public function ViewAddUser(){
        $this->authorizeAccess();
        $jurusan  = Jurusan::all();
        $keahlian = Keahlian::all();
        $angkatan = Angkatan::all();
    
        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusan = $jurusan->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_keahlian) {
            $keahlian = $keahlian->where('id_keahlian', $dosen->id_keahlian);
        }
        if ($dosen->id_angkatan) {
            $angkatan = $angkatan->where('id', $dosen->id_angkatan);
        }
        
        return view('dosen.user.views_add_user', compact('jurusan', 'keahlian', 'angkatan'));
    }

    public function AddUser(Request $request)
        {
            $this->authorizeAccess();
            $dosen = Auth::user();

            $validated = $request->validate([
                'nama_mahasiswa' => ['required','string','max:100'],
                'email' => ['nullable','email','max:100','unique:users,email'],
                'username' => ['required','string','max:100','unique:users,username','regex:/^[a-zA-Z0-9_]+$/'],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()
                ],
                'photo_profile' => [
                    'nullable',
                    'image',
                    'mimes:jpeg,png,jpg',
                    'max:2048'
                ],
                'jenis_kelamin' => ['nullable','in:Laki-laki,Perempuan,Tidak ingin memberi tahu'],
            ]);

            $photoPath = null;

            if ($request->hasFile('photo_profile')) {
                $photoPath = $request->file('photo_profile')
                    ->store('photo_profile','public');
            }

            User::create([
                'nama_mahasiswa' => $validated['nama_mahasiswa'],
                'email' => $validated['email'] ?? null,
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'photo_profile' => $photoPath,

                'role' => 'mahasiswa',

                'id_jurusan' => $dosen->id_jurusan,
                'id_keahlian' => $dosen->id_keahlian,
                'id_angkatan' => $dosen->id_angkatan,
                'status_pengajuan' => 'Di Terima',
                'is_active' => 1,
            ]);

            return redirect()
                ->route('dosen.users.index')
                ->with('success','User berhasil ditambahkan.');
        }

    public function DetailsUser($id){
        $this->authorizeAccess();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();
        
        // Filter berdasarkan dosen attributes
        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_keahlian) {
            $keahlians = $keahlians->where('id_keahlian', $dosen->id_keahlian);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }
        
        $user = User::with(['jurusan','keahlian','angkatan'])
            ->where('id', $id);
            
        // Apply filter to ensure user is within dosen's scope
        $user = $this->getdosenFilterScope($user)->firstOrFail();
        
        return view('dosen.user.views_edit_user', compact(
            'user', 
            'jurusans', 
            'keahlians', 
            'angkatans'
        ));
    }
    
    public function UpdateUser(Request $request, $id_user) {
        $this->authorizeAccess();
        $dosen = Auth::user();
        
        // Get user with filter to ensure it's within dosen's scope
        $user = User::where('id', $id_user);
        $user = $this->getdosenFilterScope($user)->firstOrFail();

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
                'in:mahasiswa,dosen,dosen'
            ],
            'status_pengajuan' => [
                'sometimes',
                'in:Di Terima,Di Tolak'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
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

        // Add validation for jurusan, keahlian, angkatan if dosen doesn't have them fixed
        if (!$dosen->id_jurusan) {
            $rules['id_jurusan'] = [
                'sometimes',
                'nullable',
                'exists:jurusan,id_jurusan'
            ];
        }
        
        if (!$dosen->id_keahlian) {
            $rules['id_keahlian'] = [
                'sometimes',
                'nullable',
                'exists:keahlian,id_keahlian'
            ];
        }
        
        if (!$dosen->id_angkatan) {
            $rules['id_angkatan'] = [
                'sometimes',
                'nullable',
                'exists:angkatan,id'
            ];
        }

        $validated = $request->validate($rules);

        $updateData = [];

        // Update basic fields
        foreach ([
            'nama_mahasiswa',
            'username',
            'email',
            'role',
            'status_pengajuan',
            'is_active'
        ] as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $validated[$field] ?? null;
            }
        }

        // Update jurusan, keahlian, angkatan only if dosen doesn't have them fixed
        if (!$dosen->id_jurusan && $request->has('id_jurusan')) {
            $updateData['id_jurusan'] = $validated['id_jurusan'] ?? null;
        }
        
        if (!$dosen->id_keahlian && $request->has('id_keahlian')) {
            $updateData['id_keahlian'] = $validated['id_keahlian'] ?? null;
        }
        
        if (!$dosen->id_angkatan && $request->has('id_angkatan')) {
            $updateData['id_angkatan'] = $validated['id_angkatan'] ?? null;
        }

        // Password update
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Role logic
        if ($request->has('role') && $validated['role'] === 'dosen') {
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
            ->route('dosen.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $user) {
        $this->authorizeAccess();
        
        // Ensure user is within dosen's scope
        $userQuery = User::where('id', $user->id);
        $user = $this->getdosenFilterScope($userQuery)->firstOrFail();
        
        $validated = $request->validate([
            'status_pengajuan' => 'required|in:Di Terima,Di Tolak',
            'keterangan_tolak' => 'required_if:status_pengajuan,Di Tolak|string|max:500',
        ]);

        $user->status_pengajuan = $validated['status_pengajuan'];

        if ($validated['status_pengajuan'] === 'Di Tolak') {
            $user->keterangan_tolak = $validated['keterangan_tolak'];
        } else {
            $user->keterangan_tolak = null;
        }

        $user->save();

        $nama = $user->nama_mahasiswa ?? $user->username;

        return redirect()
            ->route('dosen.users.index')
            ->with('success', "Status pengajuan {$nama} berhasil diupdate menjadi {$user->status_pengajuan}");
    }

    public function destroyUser(User $user){
        $this->authorizeAccess();
        
        // Ensure user is within dosen's scope
        $userQuery = User::where('id', $user->id);
        $user = $this->getdosenFilterScope($userQuery)->firstOrFail();
        
        $user->delete();

        return redirect()->route('dosen.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    //For Pages Sertifikat
    public function sertifikat(Request $request) {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');
        $status_pengajuan = $request->input('status_pengajuan');
        $selected_ids = $request->input('selected_ids', []);

        $query = Sertifikat::with('mahasiswa.jurusan', 'mahasiswa.angkatan', 'mahasiswa.keahlian');

        // Apply dosen filter scope
        $query = $this->getdosenFilterScopeForRelated($query, 'mahasiswa');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_sertifikat', 'like', '%' . $search . '%')
                  ->orWhereHas('mahasiswa', function($subQ) use ($search) {
                      $subQ->where('nama_mahasiswa', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($angkatan) {
            $query->whereHas('mahasiswa', function($q) use ($angkatan) {
                $q->where('id_angkatan', $angkatan);
            });
        }

        if ($jurusan) {
            $query->whereHas('mahasiswa', function($q) use ($jurusan) {
                $q->where('id_jurusan', $jurusan);
            });
        }

        if ($keahlian) {
            $query->whereHas('mahasiswa', function($q) use ($keahlian) {
                $q->where('id_keahlian', $keahlian);
            });
        }

        if ($status_pengajuan && $status_pengajuan !== 'Semua') {
            $query->where('status_pengajuan', $status_pengajuan);
        }

        $sertifikat = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        // Filter dropdown options based on dosen's attributes
        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_keahlian) {
            $keahlians = $keahlians->where('id_keahlian', $dosen->id_keahlian);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        $statusOptions = Sertifikat::distinct()->pluck('status_pengajuan')->filter()->values();

        return view('dosen.sertifikat', compact(
            'sertifikat', 
            'angkatans', 
            'jurusans', 
            'keahlians', 
            'statusOptions',
            'search',
            'angkatan',
            'jurusan',
            'keahlian',
            'status_pengajuan',
            'selected_ids'
        ));
    }

    public function DetailsSertifikat($id){
        $this->authorizeAccess();
        
        // Ensure sertifikat is within dosen's scope
        $sertifikat = Sertifikat::with('mahasiswa')
            ->whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($id);
            
        return view('dosen.sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }

    public function approve($id) {
        $this->authorizeAccess();
        
        // Ensure sertifikat is within dosen's scope
        $sertifikat = Sertifikat::whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($id);
            
        $sertifikat->status_pengajuan = 'Di Terima';
        $sertifikat->is_active = true;
        $sertifikat->save();
            
        return redirect()->back()->with('success', 'Sertifikat berhasil diterima');
    }

    public function reject(Request $request, $id) {
        $this->authorizeAccess();
        $request->validate(['keterangan' => 'required|string']);
        
        // Ensure sertifikat is within dosen's scope
        $sertifikat = Sertifikat::whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($id);
            
        $sertifikat->status_pengajuan = 'Di Tolak';
        $sertifikat->is_active = false;
        $sertifikat->keterangan = $request->keterangan;
        $sertifikat->save();
            
        return redirect()->back()->with('success', 'Sertifikat ditolak');
    }

    public function TambahSertifikat(Request $request) {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa');
        
        // Apply dosen filter scope
        $query = $this->getdosenFilterScope($query);
        
        // Filter pencarian berdasarkan nama mahasiswa
        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan angkatan
        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        // Filter berdasarkan keahlian
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        // Ambil data dengan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil data untuk dropdown filter
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        // Filter dropdown options based on dosen's attributes
        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_keahlian) {
            $keahlians = $keahlians->where('id_keahlian', $dosen->id_keahlian);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        return view('dosen.sertifikat.views_create_sertifikat', compact(
            'users',
            'angkatans',
            'jurusans',
            'keahlians',
            'search',
            'angkatan',
            'jurusan',
            'keahlian'
        ));
    }

    public function StoreSertifikat(Request $request){
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_sertifikat'   => 'required|string|max:255',
            'lembaga_penerbit'  => 'required|string|max:255',
            'tanggal_terbit'    => 'required|date',
            'link_sertifikat'   => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
            'user_id'           => 'required|string|max:255' 
        ]);
        
        // Ensure user is within dosen's scope
        $user = User::where('id', $validated['user_id'])
            ->where('role', 'mahasiswa');
        $user = $this->getdosenFilterScope($user)->firstOrFail();
        
        if ($request->hasFile('link_sertifikat')) {
            $path = $request->file('link_sertifikat')
                            ->store('sertifikat', 'public');
            $validated['link_sertifikat'] = $path;
        }

        Sertifikat::create([
            'id_mahasiswa'     => $user->id,
            'nama_sertifikat'  => $validated['nama_sertifikat'],
            'lembaga_penerbit' => $validated['lembaga_penerbit'],
            'tanggal_terbit'   => $validated['tanggal_terbit'],
            'link_sertifikat'  => $validated['link_sertifikat'],
            'status_pengajuan' => 'Di Terima',
            'is_active' => 1,
        ]);

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan!');  
    }

    public function UpdateSertifikat(Request $request, Sertifikat $sertifikat) {
        $this->authorizeAccess();
        
        // Ensure sertifikat is within dosen's scope
        $sertifikat = Sertifikat::whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($sertifikat->id);
        
        $validated = $request->validate([
            'nama_sertifikat'   => 'required|string|max:255',
            'lembaga_penerbit'  => 'required|string|max:255',
            'tanggal_terbit'    => 'required|date',
            'link_sertifikat'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('link_sertifikat')) {
            if ($sertifikat->link_sertifikat && 
                Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
                
                Storage::disk('public')->delete($sertifikat->link_sertifikat);
            }

            $path = $request->file('link_sertifikat')
                            ->store('sertifikat', 'public');

            $validated['link_sertifikat'] = $path;
        } else {
            $validated['link_sertifikat'] = $sertifikat->link_sertifikat;
        }

        $sertifikat->update($validated);

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    public function DestroySertifikat(Sertifikat $sertifikat) {
        $this->authorizeAccess();
        
        // Ensure sertifikat is within dosen's scope
        $sertifikat = Sertifikat::whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($sertifikat->id);
        
        $sertifikat->delete();

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function bulkDestroy(Request $request) {
        $this->authorizeAccess();
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:sertifikat,id'
        ]);

        try {
            $ids = $request->selected_ids;
            
            // Get sertifikats within dosen's scope
            $sertifikats = Sertifikat::whereIn('id', $ids)
                ->whereHas('mahasiswa', function($query) {
                    $this->getdosenFilterScope($query);
                })
                ->get();

            // Delete files from storage
            foreach ($sertifikats as $sertifikat) {
                if ($sertifikat->link_sertifikat && Storage::exists($sertifikat->link_sertifikat)) {
                    Storage::delete($sertifikat->link_sertifikat);
                }
            }

            // Delete records
            Sertifikat::whereIn('id', $sertifikats->pluck('id'))->delete();

            return response()->json([
                'success' => true,
                'message' => count($sertifikats) . ' sertifikat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    //For Projects Pages
    public function projects(){
        $this->authorizeAccess();
        
        $projects = Project::with(['mahasiswa', 'leader', 'members'])
            ->where(function($query) {
                // include projects where owner, leader, or members are in dosen filter scope
                $query->whereHas('mahasiswa', function($q) {
                    $this->getdosenFilterScope($q);
                })
                ->orWhereHas('leader', function($q) {
                    $this->getdosenFilterScope($q);
                })
                ->orWhereHas('members', function($q) {
                    $this->getdosenFilterScope($q);
                });
            })
            ->latest()
            ->paginate(12);
            
        return view('dosen.project', compact('projects'));
    }

    public function TambahProjects(Request $request){
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa');
        
        // Apply dosen filter scope
        $query = $this->getdosenFilterScope($query);
        
        // Filter pencarian berdasarkan nama mahasiswa
        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan angkatan
        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        // Filter berdasarkan keahlian
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        // Ambil data dengan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil data untuk dropdown filter
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        // Filter dropdown options based on dosen's attributes
        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_keahlian) {
            $keahlians = $keahlians->where('id_keahlian', $dosen->id_keahlian);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        return view('dosen.projects.views_create_project', compact(
            'users',
            'angkatans',
            'jurusans',
            'keahlians',
            'search',
            'angkatan',
            'jurusan',
            'keahlian'
        ));
    }

public function EditProjects($id)
{
    $this->authorizeAccess();

    $search = request()->input('search');
    $angkatan = request()->input('angkatan');
    $jurusan = request()->input('jurusan');
    $keahlian = request()->input('keahlian');

    $query = User::with(['jurusan', 'angkatan', 'keahlian'])
                ->where('role', 'mahasiswa');
    $query = $this->getDosenFilterScope($query);

    if ($search) $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
    if ($angkatan) $query->where('id_angkatan', $angkatan);
    if ($jurusan) $query->where('id_jurusan', $jurusan);
    if ($keahlian) $query->where('id_keahlian', $keahlian);

    $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    $angkatans = Angkatan::all();
    $jurusans = Jurusan::all();
    $keahlians = Keahlian::all();

    // Load project dengan relasi yang diperlukan
    $project = Project::with(['members', 'tasks', 'leader'])
        ->where('id', $id)
        ->firstOrFail();

    return view('dosen.projects.views_edit_project', compact(
        'project', 'users', 'angkatans', 'jurusans', 'keahlians', 
        'search', 'angkatan', 'jurusan', 'keahlian'
    ));
}

    public function StoreProject(Request $request) {
        $this->authorizeAccess();
        $request->merge([
            'members' => collect($request->members)
                ->filter(fn ($id) => !empty($id))
                ->values()
                ->all(),
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) || !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);
        
        $request->validate([
            'nama_project'   => 'required|string|max:255',
            'deskripsi'      => 'nullable|string|max:2000',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:500',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id|different:leader',
            'tasks' => 'nullable|array',
            'tasks.*.user_id' => 'required_with:tasks|exists:users,id',
            'tasks.*.name_task' => 'required_with:tasks|string|max:255'
        ]);

        // Ensure leader is within dosen's scope
        if ($request->leader) {
            $leader = User::where('id', $request->leader)
                ->where('role', 'mahasiswa');
            $leader = $this->getdosenFilterScope($leader)->firstOrFail();
        }

        // Ensure all members are within dosen's scope
        if ($request->members) {
            $memberIds = collect($request->members)->filter()->values()->toArray();
            if (!empty($memberIds)) {
                $members = User::whereIn('id', $memberIds)
                    ->where('role', 'mahasiswa');
                $members = $this->getdosenFilterScope($members)->get();
                
                if ($members->count() != count($memberIds)) {
                    return back()->withInput()->withErrors(['members' => 'Beberapa anggota tidak valid atau tidak dalam cakupan Anda.']);
                }
            }
        }

        // Siapkan content sebagai JSON
        $content = [
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'link_project' => $request->link_project,
            'link_github' => $request->link_github,
            'link_video' => $request->link_video
        ];

        // Filter out null values
        $content = array_filter($content, fn($value) => !is_null($value) && $value !== '');

        // Validasi minimal ada content yang diisi
        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (deskripsi, link_project, link_github, atau link_video)']);
        }

        // Buat project baru
        $project = Project::create([
            'tanggal_mulai'  => $request->tanggal_mulai,
            'tanggal_akhir'  => $request->tanggal_akhir,
            'isi_content'    => $content,
            'id_mahasiswa'   => $request->owner,
            'leader_id'      => $request->leader,
        ]);

        // Proses members (rekan project)
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($id) => $id == $request->leader || $id == $request->owner)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        // Attach members ke project (jika ada)
        if (!empty($members)) {
            $project->members()->attach($members);
        }

        // Proses tugas per orang jika dikirim
        $tasks = collect($request->input('tasks', []))->filter(function ($task) {
            return is_array($task)
                && !empty($task['user_id'])
                && !empty(trim($task['name_task'] ?? ''));
        })->values()->all();

        if (!empty($tasks)) {
            $this->createProjectTasks($project, $tasks);
        }

        return redirect()->route('dosen.projects.index')
            ->with('success', 'Project berhasil ditambahkan!')
            ->with('clear_local_storage', true);
    }

public function UpdateProject(Request $request, $id) 
{
    $this->authorizeAccess();
    
    $project = Project::findOrFail($id);
    
    $request->validate([
        'nama_project'   => 'required|string|max:255',
        'tanggal_mulai'  => 'required|date',
        'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
        'link_project'   => 'nullable|url|max:255',
        'deskripsi'      => 'nullable|string|max:255',
        'link_github'    => 'nullable|url|max:500',
        'link_video'     => 'nullable|url|max:500',
        'owner'          => 'required|exists:users,id',
        'leader'         => 'nullable|exists:users,id',
        'is_collaborative' => 'boolean',
        'members'        => 'nullable|array',
        'members.*'      => 'nullable|exists:users,id',
        'tasks'          => 'nullable|array',
        'tasks.*.id'     => 'sometimes|nullable|integer|exists:project_tasks,id',
        'tasks.*.user_id'=> 'required_with:tasks|exists:users,id',
        'tasks.*.name_task' => 'required_with:tasks|string|max:255'
    ]);

    // Prepare content array
    $content = [
        'nama_project' => $request->nama_project,
        'deskripsi'    => $request->deskripsi,
        'link_project' => $request->link_project,
        'link_github'  => $request->link_github,
        'link_video'   => $request->link_video
    ];

    // Hapus yang kosong/null
    $content = array_filter($content, fn($value) => !is_null($value) && $value !== '');

    // Minimal 1 field harus ada
    if (empty($content)) {
        return back()->withInput()
            ->withErrors(['project' => 'Minimal isi salah satu field (nama_project, deskripsi, atau link)']);
    }
    
    $isCollaborative = $request->boolean('is_collaborative', true);
    
    // Update project
    $project->update([
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_akhir' => $request->tanggal_akhir,
        'isi_content'   => $content,
        'id_mahasiswa'  => $request->owner,
        'leader_id'     => $isCollaborative ? $request->leader : null,
        'is_collaborative' => $isCollaborative,
    ]);

    // Handle members berdasarkan mode kolaboratif
    if ($isCollaborative) {
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($memberId) => $memberId == $request->owner || $memberId == $request->leader)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $project->members()->sync($members);
    } else {
        // Mode non-kolaboratif: hapus semua members
        $project->members()->sync([]);
    }

    // Handle tasks
    $this->handleTasks($project, $request->input('tasks', []), $isCollaborative, $request->owner, $request->leader);
    
    return redirect()->route('dosen.projects.index')
        ->with('success', 'Project berhasil diperbarui!');
}

private function handleTasks($project, array $tasks, bool $isCollaborative, $ownerId, $leaderId = null)
{
    $project = $project->fresh(['members', 'tasks']);

    // Kumpulkan semua user yang diizinkan
    $allowedUserIds = collect([(int) $ownerId]);

    if ($isCollaborative) {
        if ($leaderId) {
            $allowedUserIds->push((int) $leaderId);
        }
        $allowedUserIds = $allowedUserIds->merge(
            $project->members->pluck('id')->map(fn($id) => (int) $id)
        );
    }

    $allowedUserIds = $allowedUserIds->filter()->unique()->values()->all();

    $keptTaskIds = [];

    foreach ($tasks as $task) {
        $taskId     = isset($task['id']) && is_numeric($task['id']) ? (int) $task['id'] : null;
        $userId     = isset($task['user_id']) && is_numeric($task['user_id']) ? (int) $task['user_id'] : null;
        $nameTask   = isset($task['name_task']) ? trim($task['name_task']) : '';

        // Lewati jika tidak ada user_id atau nama tugas kosong
        if (!$userId || empty($nameTask)) {
            continue;
        }

        // Cek apakah user masih diizinkan
        if (!in_array($userId, $allowedUserIds, true)) {
            continue;
        }

        if ($taskId) {
            // Update existing task
            $existing = ProjectTask::where('project_id', $project->id)
                ->where('id', $taskId)
                ->first();

            if ($existing) {
                $existing->update([
                    'user_id'   => $userId,
                    'name_task' => $nameTask,
                ]);
                $keptTaskIds[] = $existing->id;
                continue;
            }
        }

        // Create new task
        $newTask = ProjectTask::create([
            'project_id' => $project->id,
            'user_id'    => $userId,
            'name_task'  => $nameTask,
            'is_done'    => false,
        ]);

        $keptTaskIds[] = $newTask->id;
    }

    // Hapus task yang tidak ada di daftar yang dikirim (kecuali yang sudah dihapus di frontend)
    if (!empty($keptTaskIds)) {
        ProjectTask::where('project_id', $project->id)
            ->whereNotIn('id', $keptTaskIds)
            ->delete();
    } else {
        // Tidak ada task yang valid → hapus semua
        ProjectTask::where('project_id', $project->id)->delete();
    }

    // Cleanup akhir: hapus task milik user yang sudah tidak boleh (misal leader diubah, member dihapus)
    ProjectTask::where('project_id', $project->id)
        ->whereNotIn('user_id', $allowedUserIds)
        ->delete();
}
   public function DestroyProject($id)
{
    $project = Project::find($id);

    if (!$project) {
        return redirect()->back()->with('error', 'Project tidak ditemukan');
    }

    $project->delete();

    return redirect()->back()->with('success', 'Project berhasil dihapus');
}

    public function bulkDestroyProject(Request $request) {
        $this->authorizeAccess();
        $ids = explode(',', $request->selected_ids);

        // Ensure only projects within dosen's scope are deleted
        Project::whereIn('id', $ids)
            ->whereHas('mahasiswa', function($query) {
                $this->getdosenFilterScope($query);
            })
            ->delete();

        return redirect()->back()->with('success', count($ids).' Project berhasil dihapus');
    }
}