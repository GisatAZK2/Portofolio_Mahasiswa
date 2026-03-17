<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\LearningCorner;
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
    public function create(){
        $users = User::select('id', 'nama_mahasiswa')
            ->whereNotIn('role', ['admin', 'dosen'])
            ->get();

        return view('project.views_create_project', compact('users'));
    }
    
    // SIMPAN BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_project'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:255',
            'deskripsi'     => 'nullable|string|max:255',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
            'leader'         => 'nullable|exists:users,id'
        ]);
        $content = array_filter($request->only([
            'nama_project', 'judul', 'deskripsi', 'link_project', 'link_github', 'link_video'
        ]), fn($value) => !is_null($value) && $value !== '');

        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (judul, deskripsi, atau link)']);
        }
        
        $project = Project::create([
        'tanggal_mulai'  => $request->tanggal_mulai,
        'tanggal_akhir'  => $request->tanggal_akhir,
        'isi_content'    => $content,
        'id_mahasiswa'   => Auth::id(),
        'leader_id'      => $request->leader,
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

    public function edit($id)
{
    $project = Project::with('members')
        ->where('id', $id)
        ->where('id_mahasiswa', Auth::id())
        ->firstOrFail();

    $users = User::select('id', 'nama_mahasiswa')
        ->whereNotIn('role', ['admin', 'dosen'])
        ->get();

    return view('project.views_edit_project', compact('project', 'users'));
    }

    // UPDATE - FIXED VERSION
    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();

        $request->validate([
            'nama_project'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:255',
            'deskripsi'      => 'nullable|string|max:255',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
            'leader'         => 'nullable|exists:users,id'
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
            'tanggal_mulai'  => $request->tanggal_mulai,
            'tanggal_akhir'  => $request->tanggal_akhir,
            'isi_content'    => $content,
            'leader_id'      => $request->leader,
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

    public function show($id){
        // Ambil semua Learning Corner milik pemilik project
        $project = Project::with('leader', 'members', 'learningCorners')
                   ->findOrFail($id);
         $entries = LearningCorner::where('project_id', $project->id)
        ->latest()
        ->get();
        return view('project.views_detail_project', compact('project', 'entries'));
    }
}