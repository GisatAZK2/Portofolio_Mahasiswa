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
use App\Models\Postingan;

class DashboardController extends Controller
{
    /**
     * Dashboard Umum (untuk semua user yang login)
     */
    public function index()
    {

        // hanya mahasiswa
        $totalMahasiswa = User::where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')->count();

        $totalLearning = LearningCorner::count();
        $totalProject = Project::count();
        $totalSertifikat = Sertifikat::count();

        $jurusanList = Jurusan::all();
        $keahlianList = Keahlian::all();
        $angkatanList = Angkatan::all();


        /*
        |--------------------------------------------------------------------------
        | POSTINGAN TERBARU - DIKELOMPOKKAN DENGAN PAGINATION
        |--------------------------------------------------------------------------
        */

        $learningCorners = LearningCorner::with('mahasiswa', 'project')
            ->latest()
            ->paginate(6, ['*'], 'learning_page');

        $learningCorners->transform(function ($item) {
            $item->type = 'learning';
            return $item;
        });

        /*
        |--------------------------------------------------------------------------
        | POSTINGAN TERBARU
        |--------------------------------------------------------------------------
        */

        $postinganTerbaru = Postingan::with(['user', 'komentar', 'likes'])
            ->latest()
            ->paginate(6, ['*'], 'postingan_page');

        $projects = Project::with('mahasiswa')
            ->latest()
            ->paginate(6, ['*'], 'project_page');

        $projects->transform(function ($item) {
            $item->type = 'project';
            return $item;
        });

        $projectUsers = Sertifikat::with('mahasiswa')
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->latest()
            ->paginate(6, ['*'], 'sertifikat_page');

        $projectUsers->transform(function ($item) {
            $item->type = 'sertifikat';
            return $item;
        });

        return view('views_dashboard', compact(
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'learningCorners',
            'projects',
            'projectUsers',
            'postinganTerbaru',
            'jurusanList',
            'keahlianList',
            'angkatanList',
        ));
    }
    /**
     * My Dashboard (Dashboard Pribadi Mahasiswa)
     */
    public function myDashboard()
    {
        $user = auth()->user();

        $totalLearning = LearningCorner::where('id_mahasiswa', $user->id)->count();
        $totalProject = Project::where(function ($query) use ($user) {
            $query->where('id_mahasiswa', $user->id)
                ->orWhere('leader_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })->count();
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
            ->where(function ($query) use ($user) {
                $query->where('id_mahasiswa', $user->id)
                    ->orWhere('leader_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
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

        $postinganTerbaru = Postingan::with(['user', 'komentar', 'likes'])
            ->latest()
            ->paginate(6, ['*'], 'postingan_page');


        $learningCorners = LearningCorner::with('mahasiswa', 'project')
            ->where('id_mahasiswa', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'learning_page');

        $learningCorners->transform(function ($item) {
            $item->type = 'learning';
            return $item;
        });

        $projects = Project::with('mahasiswa')
            ->where(function ($query) use ($user) {
                $query->where('id_mahasiswa', $user->id)
                    ->orWhere('leader_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
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
            'projectUsers',
            'postinganTerbaru'

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
    /**
     * Show portfolio for specific user
     * Handle both /Portofolio/{user} and /{locale}/Portofolio/{user} URLs
     */
    public function show($userParam, Request $request)
    {
        // If locale is passed as first param (from locale-prefixed route)
        // Adjust userParam accordingly
        $locale = request()->route('locale');
        if ($locale && in_array($locale, ['id', 'en'])) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        $user = User::where('id', $userParam)->orWhere('username', $userParam)->first();

        if (!$user) {
            abort(404);
        }

        // If accessed by ID, redirect to username URL with locale prefix
        if (is_numeric($userParam)) {
            if ($locale && in_array($locale, ['id', 'en'])) {
                return redirect()->route('portfolio.show.localized', ['locale' => $locale, 'user' => $user->username]);
            }
            return redirect()->route('portfolio.show', $user->username);
        }

        if (in_array($user->role, ['admin', 'dosen'])) {
            return redirect()->back()->with('error', 'Halaman portofolio admin atau dosen tidak diperbolehkan dibuka.');
        }

        if (($user->status_pengajuan ?? '') !== 'Di Terima') {
            return redirect()->back()->with('error', 'Portofolio belum disetujui, akses tidak diizinkan.');
        }

        $user->load([
            'jurusan',
            'angkatan',
            'keahlian',
            'keahlianTambahan' => function ($q) {
                $q->wherePivot('status_pengajuan', 'Di Terima');
            },
            'sertifikats' => function ($q) {
                $q->where('is_active', true)
                    ->where('status_pengajuan', 'Di Terima');
            },
            'learning_corners'
        ]);

        $isOwner = Auth::check() && Auth::id() === $user->id;

        $projectTab = $request->get('project_tab', 'completed');
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
        $keyword = $request->q;
        $jurusan = $request->jurusan;
        $keahlian = $request->keahlian;
        $angkatan = $request->angkatan;
        $type = $request->type;

        $results = collect();

        // ========================
        // SEARCH MAHASISWA
        // ========================
        if (!$type || $type == 'mahasiswa') {

            $users = User::with([
                'jurusan',
                'keahlian',
                'angkatan',
                'keahlianTambahan' => function ($q) {
                    $q->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                }
            ])
                ->withCount([
                    'projects',
                    'leadingProjects',
                    'memberProjects',
                    'learning_corners as learning_count',
                    'sertifikats as sertifikats_count',
                ])
                ->where('role', 'mahasiswa')
                ->where('status_pengajuan', 'Di Terima')

                ->when(
                    $keyword,
                    fn($q) =>
                    $q->where('nama_mahasiswa', 'like', "%$keyword%")
                )

                ->when(
                    $jurusan,
                    fn($q) =>
                    $q->where('id_jurusan', $jurusan)
                )

                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->where(function ($q) use ($keahlian) {
                        $q->where('id_keahlian', $keahlian)
                            ->orWhereHas('keahlianTambahan', function ($qq) use ($keahlian) {
                                $qq->where('keahlian.id_keahlian', $keahlian)
                                    ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                            });
                    });
                })

                ->when(
                    $angkatan,
                    fn($q) =>
                    $q->where('id_angkatan', $angkatan)
                )

                ->paginate(9)
                ->withQueryString();

            $users->getCollection()->transform(function ($item) {
                $item->project_total_count = Project::where(function ($q) use ($item) {
                    $q->where('id_mahasiswa', $item->id)
                        ->orWhere('leader_id', $item->id)
                        ->orWhereHas(
                            'members',
                            fn($qq) =>
                            $qq->where('user_id', $item->id)
                        );
                })->count();

                $item->type = 'mahasiswa';
                return $item;
            });

            $results = $results->concat($users->getCollection());
        }

        // ========================
        // SEARCH PROJECT
        // ========================
        if (!$type || $type === 'project') {

            $projects = Project::with([
                'mahasiswa.jurusan',
                'mahasiswa.keahlian',
                'mahasiswa.angkatan'
            ])
                ->whereHas('mahasiswa', function ($q) {
                    $q->where('role', 'mahasiswa')
                        ->where('status_pengajuan', 'Di Terima');
                })

                ->when(
                    $keyword,
                    fn($q) =>
                    $q->where('isi_content->nama_project', 'like', "%{$keyword}%")
                )

                ->when(
                    $jurusan,
                    fn($q) =>
                    $q->whereHas(
                        'mahasiswa',
                        fn($qq) =>
                        $qq->where('id_jurusan', $jurusan)
                    )
                )

                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('mahasiswa', function ($q) use ($keahlian) {
                        $q->where(function ($qq) use ($keahlian) {
                            $qq->where('id_keahlian', $keahlian)
                                ->orWhereHas('keahlianTambahan', function ($qqq) use ($keahlian) {
                                    $qqq->where('keahlian.id_keahlian', $keahlian)
                                        ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                                });
                        });
                    });
                })

                ->when(
                    $angkatan,
                    fn($q) =>
                    $q->whereHas(
                        'mahasiswa',
                        fn($qq) =>
                        $qq->where('id_angkatan', $angkatan)
                    )
                )

                ->paginate(9)
                ->withQueryString();

            $projects->getCollection()->transform(function ($project) {
                $content = $project->isi_content ?? [];

                $project->nama_project = $content['nama_project'] ?? null;
                $project->link_project = $content['link_project'] ?? null;
                $project->link_github = $content['link_github'] ?? null;
                $project->link_video = $content['link_video'] ?? null;
                $project->type = 'project';

                return $project;
            });

            $results = $results->concat($projects->getCollection());
        }

        // ========================
        // SEARCH SERTIFIKAT
        // ========================
        if (!$type || $type == 'sertifikat') {

            $sertifikats = Sertifikat::with([
                'mahasiswa.jurusan',
                'mahasiswa.keahlian',
                'mahasiswa.angkatan'
            ])
                ->where('is_active', true)
                ->where('status_pengajuan', 'Di Terima')

                ->whereHas('mahasiswa', function ($q) {
                    $q->where('role', 'mahasiswa')
                        ->where('status_pengajuan', 'Di Terima');
                })

                ->when(
                    $keyword,
                    fn($q) =>
                    $q->where('nama_sertifikat', 'like', "%$keyword%")
                )

                ->when(
                    $jurusan,
                    fn($q) =>
                    $q->whereHas(
                        'mahasiswa',
                        fn($qq) =>
                        $qq->where('id_jurusan', $jurusan)
                    )
                )

                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('mahasiswa', function ($q) use ($keahlian) {
                        $q->where(function ($qq) use ($keahlian) {
                            $qq->where('id_keahlian', $keahlian)
                                ->orWhereHas('keahlianTambahan', function ($qqq) use ($keahlian) {
                                    $qqq->where('keahlian.id_keahlian', $keahlian)
                                        ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                                });
                        });
                    });
                })

                ->when(
                    $angkatan,
                    fn($q) =>
                    $q->whereHas(
                        'mahasiswa',
                        fn($qq) =>
                        $qq->where('id_angkatan', $angkatan)
                    )
                )

                ->paginate(9)
                ->withQueryString();

            $sertifikats->getCollection()->transform(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });

            $results = $results->concat($sertifikats->getCollection());
        }

        // ========================
        // TOTAL COUNT
        // ========================
        $totalMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima')
            ->count();

        $totalLearning = LearningCorner::whereHas('mahasiswa', function ($q) {
            $q->where('role', 'mahasiswa')
                ->where('status_pengajuan', 'Di Terima');
        })->count();

        $totalProject = Project::where(function ($q) {
            $q->whereHas(
                'mahasiswa',
                fn($qq) =>
                $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')
            )
                ->orWhereHas(
                    'leader',
                    fn($qq) =>
                    $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')
                )
                ->orWhereHas(
                    'members',
                    fn($qq) =>
                    $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')
                );
        })->count();

        $totalSertifikat = Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->whereHas(
                'mahasiswa',
                fn($q) =>
                $q->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')
            )
            ->count();

        $mahasiswa = $users ?? collect();
        $projects = $projects ?? collect();
        $sertifikats = $sertifikats ?? collect();

        $hasResults =
            $mahasiswa->count() > 0 ||
            $projects->count() > 0 ||
            $sertifikats->count() > 0;

        return view('views_result_search', compact(
            'results',
            'mahasiswa',
            'projects',
            'sertifikats',
            'keyword',
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'hasResults'
        ));
    }

    /**
     * Search Suggestions API
     */
    public function searchSuggestions(Request $request)
    {
        $keyword = $request->query('q');
        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        $users = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima')
            ->where('nama_mahasiswa', 'like', "%{$keyword}%")
            ->limit(5)
            ->get(['id', 'nama_mahasiswa', 'username']);

        foreach ($users as $user) {
            $suggestions[] = [
                'type' => 'mahasiswa',
                'id' => $user->id,
                'name' => $user->nama_mahasiswa,
                'url' => route('portfolio.show', $user->username),
                'label' => 'Mahasiswa'
            ];
        }

        $projects = Project::with('mahasiswa')
            ->where('isi_content->nama_project', 'like', "%{$keyword}%")
            ->whereHas('mahasiswa', function ($q) {
                $q->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima');
            })
            ->limit(5)
            ->get();

        foreach ($projects as $project) {
            $content = $project->isi_content ?? [];
            $name = $content['nama_project'] ?? 'Project tanpa judul';
            $suggestions[] = [
                'type' => 'project',
                'id' => $project->id,
                'name' => $name,
                'url' => route('project.show', $project->id),
                'label' => 'Project'
            ];
        }

        $sertifikats = Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->where('nama_sertifikat', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        foreach ($sertifikats as $sertifikat) {
            $suggestions[] = [
                'type' => 'sertifikat',
                'id' => $sertifikat->id,
                'name' => $sertifikat->nama_sertifikat,
                'url' => '#',
                'label' => 'Sertifikat'
            ];
        }

        $postingans = Postingan::with('user')->limit(20)->get();

        foreach ($postingans as $postingan) {
            $content = $postingan->content ?? [];

            $title = collect($content)
                ->firstWhere('type', 'title')['content'] ?? null;

            if ($title && str_contains(strtolower($title), strtolower($keyword))) {
                $suggestions[] = [
                    'type' => 'postingan',
                    'id' => $postingan->id_postingan,
                    'name' => $title,
                    'url' => route('postingan.show', $postingan->id_postingan),
                    'label' => 'Postingan'
                ];
            }
        }
        return response()->json($suggestions);
    }
}
