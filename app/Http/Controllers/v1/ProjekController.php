<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\LearningCorner;
use App\Models\Angkatan;
use App\Models\Jurusan;
use App\Models\Keahlian;
use Illuminate\Support\Facades\Auth;

class ProjekController extends Controller
{
    // HALAMAN LIST (Blade)
    public function index()
    {
        $userId = Auth::id();

        $projects = Project::with(['owner', 'leader', 'members'])
            ->where(function ($query) use ($userId) {
                $query->where('id_mahasiswa', $userId)      // owner
                    ->orWhere('leader_id', $userId)       // leader
                    ->orWhereHas('members', function ($q) use ($userId) { // member
                        $q->where('user_id', $userId);
                    });
            })
            ->latest()
            ->get();

        return view('project.views_project', compact('projects'));
    }

    public function project_user()
    {
        $projects = Project::with(['mahasiswa', 'leader'])->latest()->paginate(12);
        return view('project.views_project_user', compact('projects'));
    }

    // FORM TAMBAH
    public function create(Request $request)
    {
        $search = $request->query('search', '');
        $angkatan = $request->query('angkatan', '');
        $jurusan = $request->query('jurusan', '');

        $usersQuery = User::with(['angkatan', 'jurusan'])
            ->select('id', 'nama_mahasiswa', 'photo_profile', 'email', 'id_angkatan', 'id_jurusan')
            ->whereNotIn('role', ['admin', 'dosen']);

        if (!empty($search)) {
            $usersQuery->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        if (!empty($angkatan)) {
            $usersQuery->where('id_angkatan', $angkatan);
        }

        if (!empty($jurusan)) {
            $usersQuery->where('id_jurusan', $jurusan);
        }

        $users = $usersQuery->get();

        // Get all angkatan and jurusan for filter dropdowns
        $angkatanList = Angkatan::orderBy('tahun_masuk', 'desc')->get();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();

        return view('project.views_create_project', compact('users', 'search', 'angkatan', 'jurusan', 'angkatanList', 'jurusanList'));
    }

    // SIMPAN BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullabl e|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id'
        ]);
        $content = array_filter($request->only([
            'nama_project',
            'judul',
            'deskripsi',
            'link_project',
            'link_github',
            'link_video'
        ]), fn($value) => !is_null($value) && $value !== '');

        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (judul, deskripsi, atau link)']);
        }

        $project = Project::create([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => Auth::id(),
            'leader_id' => $request->leader,
        ]);

        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($id) => $id == $request->leader)
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        if (!empty($members)) {
            $project->members()->attach($members);
        }


        return redirect()->route('project.index')
            ->with('success', 'Project berhasil ditambahkan!');
    }

    public function edit($id) {
    
    // Get search and filter inputs
    $search = request()->input('search');
    $angkatan = request()->input('angkatan');
    $jurusan = request()->input('jurusan');
    $keahlian = request()->input('keahlian');

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

    // Get project data
    $project = Project::with('members')
        ->where('id', $id)
        ->firstOrFail();

         return view('project.views_edit_project', compact('project', 'users', 'angkatans', 'jurusans', 'keahlians', 'search', 'angkatan', 'jurusan', 'keahlian'));
}


    // UPDATE - FIXED VERSION
    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id'
        ]);

        // Prepare content array
        $content = [
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'link_project' => $request->link_project,
            'link_github' => $request->link_github,
            'link_video' => $request->link_video
        ];

        // Filter out null/empty values
        $content = array_filter($content, fn($value) => !is_null($value) && $value !== '');

        // Update project
        $project->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'leader_id' => $request->leader,
        ]);

        // Handle members - FIXED PART
        // Detach all existing members first
        $project->members()->detach();

        // Add new members if any
        if ($request->has('members') && !empty($request->members)) {
            $members = collect($request->members)
                ->filter() // Remove empty values
                ->reject(fn($memberId) => $memberId == $request->leader) // Remove if same as leader
                ->unique() // Remove duplicates
                ->values()
                ->toArray();

            if (!empty($members)) {
                $project->members()->attach($members);
            }
        }

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil diperbarui!');
    }
    // HAPUS
    public function destroy($id)
    {
        $project = Project::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();

        $project->delete();

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil dihapus!');
    }

    public function show($id)
    {
        // Ambil semua Learning Corner milik pemilik project
        $project = Project::with('leader', 'members', 'learningCorners')
            ->findOrFail($id);
        $entries = LearningCorner::where('project_id', $project->id)
            ->latest()
            ->get();
        return view('project.views_detail_project', compact('project', 'entries'));
    }
}