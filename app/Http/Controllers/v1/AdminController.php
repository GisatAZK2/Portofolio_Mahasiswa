<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function mahasiswa(Request $request)
    {
        $mahasiswa = User::where('role', 'mahasiswa')
            ->with(['jurusan', 'angkatan', 'keahlian'])
            ->withCount(['projects', 'sertifikats', 'learning_corners'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.daftar-mahasiswa', compact('mahasiswa'));
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