<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
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
        return view('project.views_create_project');
    }

    // SIMPAN BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_project'   => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_akhir'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project'   => 'nullable|url|max:255',
        ]);

        $project = Project::create([
            'nama_project'   => $request->nama_project,
            'tanggal_mulai'  => $request->tanggal_mulai,
            'tanggal_akhir'  => $request->tanggal_akhir,
            'link_project'   => $request->link_project,
            'id_mahasiswa'   => Auth::id(),
        ]);

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit($id)
    {
        $project = Project::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();

        return view('project.views_edit_project', compact('project'));
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
        ]);

        $project->update($request->only([
            'nama_project', 'tanggal_mulai', 'tanggal_akhir', 'link_project'
        ]));

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
}