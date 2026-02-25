<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
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
        'learning_corners as learning_count'
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

        

        /*
        |--------------------------------------------------------------------------
        | SEARCH PROJECT
        |--------------------------------------------------------------------------
        */
        if (!$type || $type == 'project') {
            $projects = Project::with(['mahasiswa.jurusan', 'mahasiswa.keahlian'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('nama_project', 'like', "%$keyword%");
                })
                ->when($jurusan, function ($query) use ($jurusan) {
                    $query->whereHas('mahasiswa', function ($q) use ($jurusan) {
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
                    $item->type = 'project';
                    return $item;
                });

            $results = $results->concat($projects);
        }

       
        $totalMahasiswa = User::count();

        $totalLearning = LearningCorner::count();
        $totalSertifikat = Sertifikat::count();
        

        return view('views_result_search', compact(
            'results',
            'keyword',
            'totalMahasiswa',
            'totalLearning' 
        ));
    }

}
