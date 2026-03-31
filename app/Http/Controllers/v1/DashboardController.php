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
    /**
     * Dashboard Umum (untuk semua user yang login)
     */
    public function index()
    {
        // Hanya mahasiswa yang sudah diterima (status_pengajuan = 'Di Terima')
        $totalMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima')
            ->count();

        $totalLearning = LearningCorner::whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        $totalProject = Project::whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        $totalSertifikat = Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        $jurusanList = Jurusan::all();
        $jurusanList = Jurusan::all();
        $keahlianList = Keahlian::all();
        $angkatanList = Angkatan::all();

        /*
        |--------------------------------------------------------------------------
        | RANDOM POSTS (hanya dari mahasiswa diterima)
        |--------------------------------------------------------------------------
        */
        $randomLearning = LearningCorner::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'learning';
                return $item;
            });

        $randomProject = Project::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'project';
                return $item;
            });

        $projectUsers = Sertifikat::with('mahasiswa')
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });

        return view('views_dashboard', compact(
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'randomPosts',
            'jurusanList',      // jika ingin ditampilkan di view
            'keahlianList',
            'angkatanList'
        ));
    }

    /**
     * My Dashboard (Dashboard Pribadi Mahasiswa)
     */
    public function myDashboard()
    {
        $user = auth()->user();

        $totalLearning = LearningCorner::where('id_mahasiswa', $user->id)->count();
        $totalProject  = Project::where('id_mahasiswa', $user->id)->count();
        $totalSertifikat = Sertifikat::where('id_mahasiswa', $user->id)
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->count();

        $learning = LearningCorner::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->inRandomOrder()
            ->take(3)
            ->get();
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
        $project = Project::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $sertifikat = Sertifikat::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->inRandomOrder()
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | POSTINGAN TERBARU - DIKELOMPOKKAN DENGAN PAGINATION
        |--------------------------------------------------------------------------
        */

        $learningCorners = LearningCorner::with('mahasiswa', 'project')
            ->where('id_mahasiswa', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'learning_page');

        $learningCorners->transform(function ($item) {
            $item->type = 'learning';
            return $item;
        });

        $projects = Project::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'project_page');

        $projects->transform(function ($item) {
            $item->type = 'project';
            return $item;
        });

        $projectUsers = Sertifikat::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->latest()
            ->paginate(6, ['*'], 'sertifikat_page');

        $projectUsers->transform(function ($item) {
            $item->type = 'sertifikat';
            return $item;
        });

        return view('views_dashboard_me', compact(
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'learningCorners',
            'projects',
            'projectUsers'
        ));
    }

    public function paginationFragment(Request $request)
    {
        $group = $request->query('group');
        $page = $request->query('page', 1);
        $isDashboardMe = $request->query('dashboard') === 'me';
        $user = auth()->user();

        if ($group === 'learning_corner') {
            $data = $isDashboardMe
                ? LearningCorner::with('mahasiswa', 'project')->where('id_mahasiswa', $user->id)->latest()->paginate(6, ['*'], 'learning_page')
                : LearningCorner::with('mahasiswa', 'project')->latest()->paginate(6, ['*'], 'learning_page');
            $data->transform(function ($item) {
                $item->type = 'learning';
                return $item;
            });
            return view('partials.learning_corner_group', ['learningcorner' => $data]);
        } elseif ($group === 'project') {
            $data = $isDashboardMe
                ? Project::with('mahasiswa')->where('id_mahasiswa', $user->id)->latest()->paginate(6, ['*'], 'project_page')
                : Project::with('mahasiswa')->latest()->paginate(6, ['*'], 'project_page');
            $data->transform(function ($item) {
                $item->type = 'project';
                return $item;
            });
            return view('partials.project_group', ['project' => $data]);
        } elseif ($group === 'sertifikat') {
            $data = $isDashboardMe
                ? Sertifikat::with('mahasiswa')->where('id_mahasiswa', $user->id)->where('is_active', true)->where('status_pengajuan', 'Di Terima')->latest()->paginate(6, ['*'], 'sertifikat_page')
                : Sertifikat::with('mahasiswa')->where('is_active', true)->where('status_pengajuan', 'Di Terima')->latest()->paginate(6, ['*'], 'sertifikat_page');
            $data->transform(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });
            return view('partials.sertifikat_group', ['sertifikat' => $data]);
        } else {
            return response('Not found', 404);
        }
    }

    /**
     * Show Portofolio User
     */
    public function show(User $user, Request $request)
    {
        if (in_array($user->role, ['admin', 'dosen'])) {
            return redirect()->back()->with('error', 'Halaman portofolio admin atau dosen tidak diperbolehkan dibuka.');
        }

        $user->load([
            'jurusan',
            'angkatan',
            'keahlian',
            'sertifikats' => function ($q) {
                $q->where('is_active', true)
                  ->where('status_pengajuan', 'Di Terima');
            },
            'learning_corners'
        ]);

        $isOwner = Auth::check() && Auth::id() === $user->id;
        $isOwner = Auth::check() && Auth::id() === $user->id;

        $projectTab = $request->get('project_tab', 'now');
        $today = Carbon::today();

        $projectsQuery = Project::with(['owner', 'leader', 'members'])
            ->where(function ($query) use ($user) {
                $query->where('id_mahasiswa', $user->id)
                    ->orWhere('leader_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            });

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

        $projects = $projectsQuery
            ->latest()
            ->paginate(5)
            ->withQueryString();
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

    /**
     * Search Mahasiswa / Project / Sertifikat
     */
    public function search(Request $request)
    {
        $keyword  = $request->q;
        $jurusan  = $request->jurusan;
        $keahlian = $request->keahlian;
        $angkatan = $request->angkatan;
        $type     = $request->type;

        $results = collect();

        // SEARCH MAHASISWA
        if (!$type || $type == 'mahasiswa') {
            $users = User::with(['jurusan', 'keahlian', 'angkatan'])
                ->withCount([
                    'projects',
                    'learning_corners as learning_count',
                    'sertifikats as sertifikats_count',
                ])
                ->where('role', 'mahasiswa')
                ->where('status_pengajuan', 'Di Terima')
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('nama_mahasiswa', 'like', "%$keyword%");
                })
                ->when($jurusan, function ($query) use ($jurusan) {
                    $query->where('id_jurusan', $jurusan);
                })
                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('keahlian', fn($q) => $q->where('id', $keahlian)); // sesuaikan jika relasi many-to-many
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

        // SEARCH PROJECT
        if (!$type || $type === 'project') {
            $projects = Project::with(['mahasiswa.jurusan', 'mahasiswa.keahlian', 'mahasiswa.angkatan'])
                ->whereHas('mahasiswa', function ($query) {
                    $query->where('role', 'mahasiswa')
                          ->where('status_pengajuan', 'Di Terima');
                })
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
                ->values();

            $results = $results->concat($projects);
        }

        // SEARCH SERTIFIKAT
        if (!$type || $type == 'sertifikat') {
            $sertifikats = Sertifikat::with(['mahasiswa.jurusan', 'mahasiswa.keahlian', 'mahasiswa.angkatan'])
                ->where('is_active', true)
                ->where('status_pengajuan', 'Di Terima')
                ->whereHas('mahasiswa', function ($query) {
                    $query->where('role', 'mahasiswa')
                          ->where('status_pengajuan', 'Di Terima');
                })
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('nama_sertifikat', 'like', "%$keyword%");
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
                ->map(function ($item) {
                    $item->type = 'sertifikat';
                    return $item;
                });

            $results = $results->concat($sertifikats);
        }

        // Total untuk ditampilkan di halaman search (sudah difilter)
        $totalMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima')
            ->count();

        $totalLearning = LearningCorner::whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        $totalProject = Project::whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        $totalSertifikat = Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('role', 'mahasiswa')
                      ->where('status_pengajuan', 'Di Terima');
            })->count();

        return view('views_result_search', compact(
            'mahasiswa',
            'projects',
            'sertifikats',
            'keyword',
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat'
        ));
    }
}