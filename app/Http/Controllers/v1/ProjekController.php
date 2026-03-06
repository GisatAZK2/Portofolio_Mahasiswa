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
        $projects = Project::with('mahasiswa')
            ->where('id_mahasiswa', Auth::id())
            ->latest()
            ->get();

        return view('project.views_project', compact('projects'));
    }

    public function project_user()
    {
        $projects = Project::with('mahasiswa')->latest()->get();
        return view('project.views_project_user', compact('projects'));
    }

    // FORM TAMBAH
    public function create()
    {
        $users = User::select('id', 'nama_mahasiswa')->get() ;
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

    // FORM EDIT
    public function edit($id)
    {
        $project = Project::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();
        $users = User::select('id', 'nama_mahasiswa')->get();
        return view('project.views_edit_project', compact('project', 'users'));
    }

    // UPDATE
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
        $content = array_filter($request->only([
            'nama_project', 'judul', 'deskripsi', 'link_project', 'link_github', 'link_video'
        ]), fn($value) => !is_null($value) && $value !== '');

        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (judul, deskripsi, atau link)']);
        }
        
        $project->update([
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
            $project->members()->detach();
        }

        /*Penghapusan Link yang terpilih ?
        foreach (['link_project', 'link_github', 'link_video'] as $link) {

        // Kalau link dicentang untuk dihapus → skip
        if (in_array($link, $removeLinks)) {
            continue;
        }

        // Kalau ada value baru → update
        if ($request->filled($link)) {
            $content[$link] = $request->$link;
        }
    } */
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