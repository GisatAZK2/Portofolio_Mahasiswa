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
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu.');
        }

        // Admin diizinkan mengakses untuk membantu membuat portofolio
        if ($user->role !== 'admin' && $user->role !== 'superadmin') {
            abort(Response::HTTP_FORBIDDEN, 'Akses hanya untuk Administrator.');
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
    public function index() {
        $this->authorizeAccess();

        $totalUsers = User::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalDosen = User::where('role', 'dosen')->count();

        $totalProjects = Project::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();
        $totalLearningCorners = LearningCorner::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();
        $totalSertifikats = Sertifikat::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();

        $latestActivities = Project::with('mahasiswa')
            ->latest('created_at')
            ->take(3)
            ->get();

        $pendingMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->latest('created_at')
            ->take(3)
            ->get();

        $rejectedMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Tolak')
            ->latest('created_at')
            ->take(3)
            ->get();

        return view('admin.index', compact(
            'totalUsers',
            'totalMahasiswa',
            'totalAdmin',
            'totalDosen',
            'totalProjects',
            'totalLearningCorners',
            'totalSertifikats',
            'latestActivities',
            'pendingMahasiswa',
            'rejectedMahasiswa'
            
        ));
    }

    //For Pages User
    public function ListUser(Request $request){
        $this->authorizeAccess();
        $users = User::whereIn('role', ['mahasiswa', 'admin', 'dosen'])
            ->where('id', '!=', Auth::id()) // tidak tampilkan user yang sedang login
            ->with(['jurusan', 'angkatan', 'keahlian'])
            ->withCount([
                'projects',
                'sertifikats',
                'learning_corners'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->has('search')) {
            $search = $request->input('search');
            $users = $users->filter(function ($user) use ($search) {
                return str_contains(strtolower($user->nama_mahasiswa), strtolower($search)) ||
                       str_contains(strtolower($user->username), strtolower($search)) ||
                       str_contains(strtolower($user->email), strtolower($search));
            });
        }

        return view('admin.daftar-mahasiswa', compact('users'));
    }

    public function ViewAddUser(){
        $this->authorizeAccess();
        $jurusan  = Jurusan::all();
        $keahlian = Keahlian::all();
        $angkatan = Angkatan::all();
        return view('admin.user.views_add_user',compact('jurusan', 'keahlian', 'angkatan'));
    }

    public function AddUser(Request $request){
        $this->authorizeAccess();
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

    public function DetailsUser($id){
            $this->authorizeAccess();
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
    public function UpdateUser(Request $request, $id_user) {
        $this->authorizeAccess();
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

    public function updateStatus(Request $request, User $user) {
        $this->authorizeAccess();
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
            ->route('admin.users.index')
            ->with('success', "Status pengajuan {$nama} berhasil diupdate menjadi {$user->status_pengajuan}");
    }

    public function destroyUser(User $user){
         $this->authorizeAccess();
         $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function bulkDestroyUsers(Request $request) {
            $ids = $request->input('selected_ids');

            // Handle jika dikirim sebagai JSON string (dari JS)
            if (is_string($ids)) {
                $ids = json_decode($ids, true);
            }

            if (empty($ids) || !is_array($ids)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada pengguna yang dipilih untuk dihapus.');
            }

            // Optional: Tambahkan pengecekan agar admin tidak bisa menghapus dirinya sendiri
            $currentUserId = Auth::id();
            $ids = array_filter($ids, function($id) use ($currentUserId) {
                return $id != $currentUserId;
            });

            $count = User::whereIn('id', $ids)->delete();

            return redirect()->route('admin.users.index')
                ->with('success', "{$count} pengguna berhasil dihapus secara permanen.");
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

        $statusOptions = Sertifikat::distinct()->pluck('status_pengajuan')->filter()->values();

        return view('admin.sertifikat', compact(
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
            $sertifikat = Sertifikat::findorfail($id);
            return view('admin.sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }

    public function approve($id) {
        $this->authorizeAccess();
        $sertifikat = Sertifikat::findOrFail($id);
        $sertifikat->status_pengajuan = 'Di Terima';
        $sertifikat->is_active = true;
        $sertifikat->save();
            
        return redirect()->back()->with('success', 'Sertifikat berhasil diterima');
    }

    public function reject(Request $request, $id) {
        $this->authorizeAccess();
        $request->validate(['keterangan' => 'required|string']);
            
        $sertifikat = Sertifikat::findOrFail($id);
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

        return view('admin.sertifikat.views_create_sertifikat', compact(
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
            'user_id'              => 'required|string|max:255' 
        ]);
        
        if ($request->hasFile('link_sertifikat')) {
            $path = $request->file('link_sertifikat')
                            ->store('sertifikat', 'public');
            $validated['link_sertifikat'] = $path;
        }

        Sertifikat::create([
            'id_mahasiswa'     => $validated['user_id'],
            'nama_sertifikat'  => $validated['nama_sertifikat'],
            'lembaga_penerbit' => $validated['lembaga_penerbit'],
            'tanggal_terbit'   => $validated['tanggal_terbit'],
            'link_sertifikat'  => $validated['link_sertifikat'],

            'status_pengajuan' => 'Di Terima',
            'is_active' => 1,
        ]);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan!');  
    }

    public function UpdateSertifikat(Request $request, Sertifikat $sertifikat) {
        $this->authorizeAccess();
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

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    public function DestroySertifikat(Sertifikat $sertifikat) {
        $this->authorizeAccess();
        $sertifikat->delete();

        return redirect()->route('admin.sertifikat.index')
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
            $sertifikats = Sertifikat::whereIn('id', $ids)->get();

            // Delete files from storage
            foreach ($sertifikats as $sertifikat) {
                if ($sertifikat->link_sertifikat && Storage::exists($sertifikat->link_sertifikat)) {
                    Storage::delete($sertifikat->link_sertifikat);
                }
            }

            // Delete records
            Sertifikat::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' sertifikat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    // For Pages Angkatan
    public function ListAngkatan() {
        $this->authorizeAccess();
        $angkatans = Angkatan::all();
        return view('admin.list-angkatan', compact('angkatans'));
    }

    public function DetailsAngkatan($id){
            $this->authorizeAccess();
            $angkatan = Angkatan::withCount('mahasiswa')->findorfail($id);
            return view('admin.angkatan.views_detail_angkatan', compact('angkatan'));
    }

    public function TambahAngkatan() {
        $this->authorizeAccess();
        return view('admin.angkatan.views_create_angkatan');
    }

    public function StoreAngkatan(Request $request) {
         $this->authorizeAccess();
         $validated = $request->validate([
            'nama_angkatan'   => 'required|string|max:255',
            'tahun_masuk'     => 'required|date',
            'tahun_keluar'    => 'required|date',
        ]);

        Angkatan::create([
            'nama_angkatan' => $validated['nama_angkatan'],
            'tahun_masuk'   => $validated['tahun_masuk'],
            'tahun_keluar'  => $validated['tahun_keluar']
        ]);

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil ditambahkan!');  
    }

    public function UpdateAngkatan(Request $request, Angkatan $angkatan) {
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_angkatan'   => 'required|string|max:255',
            'tahun_masuk'     => 'required|date',
            'tahun_keluar'    => 'required|date',
        ]);

        $angkatan->update($validated);

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Data Angkatan berhasil diperbarui!');
    }

    public function DestroyAngkatan(Angkatan $angkatan) {
        $this->authorizeAccess();
        $angkatan->delete();

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil dihapus.');
    }

    public function bulkDestroyAngkatan(Request $request) {
        $this->authorizeAccess();
        $ids = explode(',', $request->selected_ids);

        Angkatan::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids).' Angkatan berhasil dihapus');
    }

    //For Projects Pages
    public function projects(){
        $this->authorizeAccess();
        $projects = Project::with(['mahasiswa', 'leader'])->latest()->paginate(12);
        return view('admin.project', compact('projects'));
    }

    
    public function TambahProjects(Request $request)
    {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima');
        
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

        return view('admin.projects.views_create_project', compact(
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
        
        // Get search and filter inputs
        $search = request()->input('search');
        $angkatan = request()->input('angkatan');
        $jurusan = request()->input('jurusan');
        $keahlian = request()->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima');
        
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

        // Get project data
        $project = Project::with(['members', 'tasks'])
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.projects.views_edit_project', compact(
            'project', 
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

    public function StoreProject(Request $request) 
    {
        $this->authorizeAccess();
        
        // Merge dan filter members
        $request->merge([
            'members' => collect($request->members)
                ->filter(fn ($id) => !empty($id))
                ->values()
                ->all(),
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) && !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);
        
        // Validasi
        $validated = $request->validate([
            'nama_project'   => 'required|string|max:255',
            'deskripsi'      => 'nullable|string|max:2000',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:500',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
            'owner'          => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima', // Owner (id_mahasiswa)
            'leader'         => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima', // Leader (leader_id)
            'members'        => 'nullable|array',
            'members.*'      => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks'          => 'nullable|array',
            'tasks.*.user_id' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks.*.name_task' => 'nullable|string|max:255'
        ]);
        
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
        
        // Set owner dan leader
        $ownerId = $request->owner ?: null;
        $leaderId = $request->leader ?: null;
        
        // Jika leader tidak diisi, gunakan owner sebagai leader
        if (!$leaderId && $ownerId) {
            $leaderId = $ownerId;
        }
        
        // Buat project baru
        $project = Project::create([
            'tanggal_mulai'  => $request->tanggal_mulai,
            'tanggal_akhir'  => $request->tanggal_akhir,
            'isi_content'    => $content,
            'id_mahasiswa'   => $ownerId, // Owner project
            'leader_id'      => $leaderId, // Leader project
        ]);
        
        // Proses members (rekan project)
        $members = collect($request->members ?? [])
            ->filter() // Hapus nilai kosong
            ->reject(fn($id) => $id == $ownerId || $id == $leaderId) // Pastikan tidak duplikasi dengan owner/leader
            ->map(fn($id) => (int) $id)
            ->unique() // Hapus duplikasi ID
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

       
        // Hanya proses tasks jika ada
        if (!empty($tasks)) {
            $this->createProjectTasks($project, $tasks);
        }
        
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil ditambahkan!')
            ->with('clear_local_storage', true);
    }
    
    public function UpdateProject(Request $request, $id) 
    {
        $this->authorizeAccess();
        
        $project = Project::findOrFail($id);
        
        $request->merge([
            'members' => collect($request->members)
                ->filter(fn ($id) => !empty($id))
                ->values()
                ->all(),
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) && !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);
        
        $validated = $request->validate([
            'nama_project'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:255',
            'deskripsi'      => 'nullable|string|max:2000',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
            'owner'          => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'leader'         => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'members'        => 'nullable|array',
            'members.*'      => 'exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks'          => 'nullable|array',
            'tasks.*.id'     => 'sometimes|nullable|integer|exists:project_tasks,id',
            'tasks.*.user_id'=> 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks.*.name_task' => 'nullable|string|max:255',
            'is_collaborative' => 'nullable|boolean'
        ]);
        
        // Prepare content array
        $content = [];
        if ($request->filled('nama_project')) $content['nama_project'] = $request->nama_project;
        if ($request->filled('deskripsi')) $content['deskripsi'] = $request->deskripsi;
        if ($request->filled('link_project')) $content['link_project'] = $request->link_project;
        if ($request->filled('link_github')) $content['link_github'] = $request->link_github;
        if ($request->filled('link_video')) $content['link_video'] = $request->link_video;
        
        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (nama_project, deskripsi, atau link)']);
        }
        
        $ownerId = $request->owner ?: null;
        $leaderId = $request->leader ?: null;
        $isCollaborative = $request->boolean('is_collaborative', true);

        // Jika leader tidak diisi atau mode non-kolaboratif, gunakan owner sebagai leader
        if (!$leaderId && $ownerId) {
            $leaderId = $ownerId;
        }
        if (!$isCollaborative && $ownerId) {
            $leaderId = $ownerId;
        }

        // Update project
        $project->update([
            'tanggal_mulai'  => $request->tanggal_mulai,
            'tanggal_akhir'  => $request->tanggal_akhir,
            'isi_content'    => $content,
            'id_mahasiswa'   => $ownerId,
            'leader_id'      => $leaderId,
        ]);

        // Handle members
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($memberId) => $memberId == $ownerId || $memberId == $leaderId)
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        if (!$isCollaborative) {
            $members = [];
        }
        
        // Handle tasks
        $submittedTasks = collect($request->input('tasks', []))
            ->filter(fn($task) => !empty($task['user_id']) && !empty(trim($task['name_task'] ?? '')))
            ->values()
            ->all();
        
        if (!empty($submittedTasks)) {
            $savedTaskIds = $this->createProjectTasks($project, $submittedTasks);
            
            // Hapus task yang tidak ada dalam list yang disimpan
            if (!empty($savedTaskIds)) {
                ProjectTask::where('project_id', $project->id)
                    ->whereNotIn('id', $savedTaskIds)
                    ->delete();
            } else {
                ProjectTask::where('project_id', $project->id)->delete();
            }
        } else {
            // Jika tidak ada tasks yang disubmit, hapus semua tasks
            ProjectTask::where('project_id', $project->id)->delete();
        }

        // Cleanup tasks yang tidak lagi terkait dengan owner/leader/member saat update
        $allowedUserIds = collect([$ownerId, $leaderId])
            ->merge($members)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($allowedUserIds)) {
            ProjectTask::where('project_id', $project->id)
                ->whereNotIn('user_id', $allowedUserIds)
                ->delete();
        } else {
            ProjectTask::where('project_id', $project->id)->delete();
        }

        if (!$isCollaborative && $ownerId) {
            ProjectTask::where('project_id', $project->id)
                ->where('user_id', '!=', $ownerId)
                ->delete();
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil diperbarui!');
    }

    

   public function DestroyProject(Project $project)
    {
        $this->authorizeAccess();
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project Mahasiswa berhasil dihapus!');
    }

    public function bulkDestroyProject(Request $request) {
        $this->authorizeAccess();

        $selectedIds = $request->input('selected_ids', []);
        if (!is_array($selectedIds)) {
            $selectedIds = array_filter(explode(',', $selectedIds), fn($value) => trim($value) !== '');
        }

        $ids = array_values(array_filter(array_map('intval', $selectedIds)));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada project terpilih untuk dihapus.');
        }

        Project::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids).' Project berhasil dihapus');
    }

}