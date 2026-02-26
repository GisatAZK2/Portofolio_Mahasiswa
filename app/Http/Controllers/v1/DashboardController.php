<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LearningCorner;
use App\Models\Project;
use App\Models\Jurusan;
use App\Models\Angkatan;
use App\Models\Keahlian;
use App\Models\Sertifikat;

class DashboardController extends Controller
{
    public function index()
    {
       
        $totalMahasiswa = User::count();
        $totalLearning = LearningCorner::count();
        $totalProject = Project::count();
        $jurusanList  = Jurusan::all();
        $keahlianList = Keahlian::all();
        $angkatanList = Angkatan::all();
        $totalSertifikat= Sertifikat::count();


        // =========================
        // AMBIL POSTING RANDOM
        // =========================


        $randomLearning = LearningCorner::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'learning';
                return $item;
            });

        $randomProject = Project::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'project';
                return $item;
            });

         $randomSertifikat = Sertifikat::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });


        $randomPosts = $randomProject
            ->concat($randomLearning)
            ->concat($randomSertifikat)
            ->shuffle()
            ->take(6);

        return view('views_dashboard', compact(
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'randomPosts',
        ));
    }

    public function myDashboard()
{
    $userId = auth()->id();

    $totalLearning = LearningCorner::where('id_mahasiswa', $userId)->count();
    $totalProject = Project::where('id_mahasiswa', $userId)->count();
    $totalSertifikat = Sertifikat::where('id_mahasiswa', $userId)->count();

    // =========================
    // AMBIL POSTING RANDOM USER
    // =========================

    $randomLearning = LearningCorner::with('mahasiswa')
        ->where('id_mahasiswa', $userId)
        ->inRandomOrder()
        ->take(3)
        ->get()
        ->map(function ($item) {
            $item->type = 'learning';
            return $item;
        });

    $randomProject = Project::with('mahasiswa')
        ->where('id_mahasiswa', $userId)
        ->inRandomOrder()
        ->take(3)
        ->get()
        ->map(function ($item) {
            $item->type = 'project';
            return $item;
        });

    $randomSertifikat = Sertifikat::with('mahasiswa')
        ->where('id_mahasiswa', $userId)
        ->inRandomOrder()
        ->take(3)
        ->get()
        ->map(function ($item) {
            $item->type = 'sertifikat';
            return $item;
        });

    $randomPosts = $randomProject
        ->concat($randomLearning)
        ->concat($randomSertifikat)
        ->shuffle()
        ->take(6);

    return view('views_dashboard_me', compact(
        'totalLearning',
        'totalProject',
        'totalSertifikat',
        'randomPosts'
    ));
}

    public function show(User $user)
    {
        $user->load([
            'projects',
            'learning_corners',
            'jurusan',
            'angkatan',
            'sertifikats',
            'keahlian'
        ]);

        $isOwner = Auth::check() && Auth::id() === $user->id;

        return view('views_portofolio_user', compact('user', 'isOwner'));
    }

    public function search(Request $request)
    {
        $keyword   = $request->q;
        $jurusan   = $request->jurusan;
        $keahlian  = $request->keahlian;
        $angkatan  = $request->angkatan;
        $type      = $request->type;

        $results = collect();

        /*
        |--------------------------------------------------------------------------
        | SEARCH USER
        |--------------------------------------------------------------------------
        */
        if (!$type || $type == 'mahasiswa') {
            $users = User::with(['jurusan', 'keahlian', 'angkatan'])
                ->withCount([
    'projects',
    'learning_corners as learning_count',
    'sertifikats as sertifikats_count',
])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('nama_mahasiswa', 'like', "%$keyword%");
                })
                ->when($jurusan, function ($query) use ($jurusan) {
                    $query->where('id_jurusan', $jurusan);
                })
                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->where('id_keahlian', $keahlian);
                })
                ->when($angkatan, function ($query) use ($angkatan) {
                    $query->where('id_angkatan', $angkatan);
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'mahasiswa';
                    return $item;
                });

            $results = $results->concat($users);
        }

        if ($type === null || $type === 'project') {
    $projects = Project::with(['mahasiswa.jurusan', 'mahasiswa.keahlian', 'mahasiswa.angkatan'])
        ->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('isi_content->nama_project', 'like', "%{$keyword}%");
            });
        })
        ->when($jurusan, function ($query) use ($jurusan) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('id_jurusan', $jurusan));
        })
        ->when($keahlian, function ($query) use ($keahlian) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('id_keahlian', $keahlian));
        })
        ->when($angkatan, function ($query) use ($angkatan) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('id_angkatan', $angkatan));
        })
        ->get()
        ->map(function ($project) {
            $content = $project->isi_content ?? [];

            $project->nama_project  = $content['nama_project'] ?? null;
            $project->link_project  = $content['link_project'] ?? null;
            $project->link_github   = $content['link_github'] ?? null;
            $project->link_video    = $content['link_video'] ?? null;
            $project->type = 'project';

            return $project;
        });

    $results = $results->concat($projects);
}
        /*
        |--------------------------------------------------------------------------
        | SEARCH SERTIFIKAT
        |--------------------------------------------------------------------------
        */
        if (!$type || $type == 'sertifikat') {
            $sertifikats = Sertifikat::with(['mahasiswa.jurusan', 'mahasiswa.keahlian'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('nama_sertifikat', 'like', "%$keyword%");     
                })
                ->when($jurusan, function ($query) use ($jurusan) {
                    $query->whereHas('mahasiswa', function ($q) use ($jurusan)
                        {
                            $q->where('id_jurusan', $jurusan);
                        });
                })
                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('mahasiswa', function ($q) use ($keahlian) {
                        $q->where('id_keahlian', $keahlian);
                    });
                })
                ->when($angkatan, function ($query) use ($angkatan) {
                    $query->whereHas('mahasiswa', function ($q) use ($angkatan) {
                        $q->where('id_angkatan', $angkatan);
                    });
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'sertifikat';
                    return $item;
        
                });

            $results = $results->concat($sertifikats);
        }

        /* SEARCH ANGKATAN */
                

       
        $totalMahasiswa = User::count();

        $totalLearning = LearningCorner::count();
        $totalSertifikat = Sertifikat::count();
        $totalProject = Project::count();
        

        return view('views_result_search', compact(
            'results',
            'keyword',
            'totalMahasiswa',
            'totalSertifikat',
            'totalProject'
        ));
    }

}
