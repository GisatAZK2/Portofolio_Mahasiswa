<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
       
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
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
    $user = auth()->user();

    // ======================
    // DOSEN
    // ======================
    if ($user->role === 'dosen') {

        $totalMahasiswa = User::where('role', 'mahasiswa')->count();

        $totalLearning = LearningCorner::count();
        $totalProject = Project::count();
        $totalSertifikat = Sertifikat::count();

        $learning = LearningCorner::with('mahasiswa')->inRandomOrder()->take(3)->get();
        $project = Project::with('mahasiswa')->inRandomOrder()->take(3)->get();
        $sertifikat = Sertifikat::with('mahasiswa')->inRandomOrder()->take(3)->get();
    }

    // ======================
    // MAHASISWA
    // ======================
    else {

        $totalMahasiswa = null;

        $totalLearning = LearningCorner::where('id_mahasiswa', $user->id)->count();
        $totalProject = Project::where('id_mahasiswa', $user->id)->count();
        $totalSertifikat = Sertifikat::where('id_mahasiswa', $user->id)->count();

        $learning = LearningCorner::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $project = Project::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $sertifikat = Sertifikat::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    

    // =========================
    // AMBIL POSTING RANDOM USER
    // =========================

    $learning = $learning->map(function ($item) {
        $item->type = 'learning';
        return $item;
    });

    $project = $project->map(function ($item) {
        $item->type = 'project';
        return $item;
    });

    $sertifikat = $sertifikat->map(function ($item) {
        $item->type = 'sertifikat';
        return $item;
    });

    $randomPosts = $learning
        ->concat($project)
        ->concat($sertifikat)
        ->shuffle()
        ->take(6);

    return view('views_dashboard_me', compact(
        'totalMahasiswa',
        'totalLearning',
        'totalProject',
        'totalSertifikat',
        'randomPosts'
    ));
}

public function show(User $user, Request $request)
{
    // Load relasi user
    $user->load([
        'jurusan',
        'angkatan',
        'keahlian',
        'sertifikats',
        'learning_corners'
    ]);

    // cek apakah owner profile
    $isOwner = Auth::check() && Auth::id() === $user->id;

    $projectTab = $request->get('project_tab', 'now');
    $today = Carbon::today();

    // Query project dimana user adalah owner / leader / member
    $projectsQuery = Project::with(['owner', 'leader', 'members'])
        ->where(function ($query) use ($user) {
            $query->where('id_mahasiswa', $user->id) // owner
                  ->orWhere('leader_id', $user->id) // leader
                  ->orWhereHas('members', function ($q) use ($user) { // member
                        $q->where('users.id', $user->id);
                  });
        });

    // Filter berdasarkan tab
    switch ($projectTab) {

        case 'upcoming':
            $projectsQuery->where('tanggal_mulai', '>', $today);
            break;

        case 'completed':
            $projectsQuery->whereNotNull('tanggal_akhir')
                          ->where('tanggal_akhir', '<', $today);
            break;

        case 'now':
        default:
            $projectsQuery
                ->where('tanggal_mulai', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->where('tanggal_akhir', '>=', $today)
                      ->orWhereNull('tanggal_akhir');
                });
            break;
    }

    // Pagination
    $projects = $projectsQuery
        ->latest()
        ->paginate(5)
        ->withQueryString();

    return view('views_portofolio_user', compact(
        'user',
        'projects',
        'projectTab',
        'isOwner'
    ));
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
            $query->where('isi_content->nama_project', 'like', "%{$keyword}%");
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

        // 🔥 cegah project muncul 2x
        ->unique('id')

        ->map(function ($project) {

            $content = $project->isi_content ?? [];

            $project->nama_project = $content['nama_project'] ?? null;
            $project->link_project = $content['link_project'] ?? null;
            $project->link_github  = $content['link_github'] ?? null;
            $project->link_video   = $content['link_video'] ?? null;
            $project->type = 'project';

            return $project;
        })

        ->values(); // reset index

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
