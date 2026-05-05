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
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')->count();
        $totalLearning  = LearningCorner::count();
        $totalProject   = Project::count();
        $totalSertifikat = Sertifikat::count();

        $jurusanList  = Jurusan::all()  ?? collect();
        $keahlianList = Keahlian::all() ?? collect();
        $angkatanList = Angkatan::all() ?? collect();

        $learningCorners = LearningCorner::with(['mahasiswa', 'project'])
            ->latest()
            ->paginate(6, ['*'], 'learning_page')
            ->fragment('learning-corner-list');

        if ($learningCorners && $learningCorners->isNotEmpty()) {
            $learningCorners->getCollection()->transform(function ($item) {
                $item->type = 'learning';
                return $item;
            });
        }

        $postinganTerbaru = Postingan::with(['user', 'komentar', 'likes', 'game'])
            ->leftJoin(DB::raw('
                (SELECT * FROM games g1
                WHERE g1.created_at = (
                    SELECT MIN(g2.created_at)
                    FROM games g2
                    WHERE g2.id_postingan = g1.id_postingan
                )) as games_oldest
            '), 'postingan.id_postingan', '=', 'games_oldest.id_postingan')
            ->select('postingan.*')
            ->orderByRaw('CASE WHEN games_oldest.id_games IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('postingan.created_at', 'desc')
            ->paginate(10, ['*'], 'postingan_page')
            ->fragment('postingan-content-wrapper');

        $projects = Project::with('mahasiswa')
            ->latest()
            ->paginate(6, ['*'], 'project_page')
            ->fragment('projects-page');

        if ($projects && $projects->isNotEmpty()) {
            $projects->getCollection()->transform(function ($item) {
                $item->type = 'project';
                return $item;
            });
        }

        $dosenList = User::where(function ($q) {
            $q->where('role', 'dosen')
              ->orWhere('role', 'Dosen')
              ->orWhere('role', 'DOSEN');
        })->where('status_pengajuan', 'Di Terima')->get() ?? collect();

        $projectUsers = Sertifikat::with('mahasiswa')
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->latest()
            ->paginate(6, ['*'], 'sertifikat_page')
            ->fragment('sertifikats-section');

        if ($projectUsers && $projectUsers->isNotEmpty()) {
            $projectUsers->getCollection()->transform(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });
        }

        return view('views_dashboard', compact(
            'totalMahasiswa', 'totalLearning', 'totalProject', 'totalSertifikat',
            'learningCorners', 'projects', 'projectUsers', 'postinganTerbaru',
            'jurusanList', 'keahlianList', 'angkatanList', 'dosenList',
        ));
    }

    public function myDashboard()
    {
        $user = auth()->user();

        $totalLearning = LearningCorner::where('id_mahasiswa', $user->id)->count();
        $totalProject  = Project::where(function ($q) use ($user) {
            $q->where('id_mahasiswa', $user->id)
              ->orWhere('leader_id', $user->id)
              ->orWhereHas('members', fn($qq) => $qq->where('user_id', $user->id));
        })->count();
        $totalSertifikat = Sertifikat::where('id_mahasiswa', $user->id)
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->count();

        $postinganTerbaru = Postingan::with(['user', 'komentar', 'likes'])
            ->where('id_user', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'postingan_page')
            ->fragment('postingan-section');

        $learningCorners = LearningCorner::with('mahasiswa', 'project')
            ->where('id_mahasiswa', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'learning_page')
            ->fragment('learning-corner-list');

        $learningCorners->transform(function ($item) {
            $item->type = 'learning';
            return $item;
        });

        $projects = Project::with('mahasiswa')
            ->where(function ($q) use ($user) {
                $q->where('id_mahasiswa', $user->id)
                  ->orWhere('leader_id', $user->id)
                  ->orWhereHas('members', fn($qq) => $qq->where('user_id', $user->id));
            })
            ->latest()
            ->paginate(6, ['*'], 'project_page')
            ->fragment('projects-section');

        $projects->transform(function ($item) {
            $item->type = 'project';
            return $item;
        });

        $projectUsers = Sertifikat::with('mahasiswa')
            ->where('id_mahasiswa', $user->id)
            ->where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->latest()
            ->paginate(6, ['*'], 'sertifikat_page')
            ->fragment('sertifikat-section');

        $projectUsers->transform(function ($item) {
            $item->type = 'sertifikat';
            return $item;
        });

        return view('views_dashboard_me', compact(
            'totalLearning', 'totalProject', 'totalSertifikat',
            'learningCorners', 'projects', 'projectUsers', 'postinganTerbaru'
        ));
    }

    public function paginationFragment(Request $request)
    {
        $group        = $request->query('group');
        $isDashboardMe = $request->query('dashboard') === 'me';
        $user         = auth()->user();

        if ($group === 'learning_corner') {
            $data = $isDashboardMe
                ? LearningCorner::with('mahasiswa', 'project')->where('id_mahasiswa', $user->id)->latest()->paginate(6, ['*'], 'learning_page')
                : LearningCorner::with('mahasiswa', 'project')->latest()->paginate(6, ['*'], 'learning_page');
            $data->transform(fn($item) => tap($item, fn($i) => $i->type = 'learning'));
            return view('partials.learning_corner_group', ['learningcorner' => $data]);

        } elseif ($group === 'project') {
            $data = $isDashboardMe
                ? Project::with('mahasiswa')->where('id_mahasiswa', $user->id)->latest()->paginate(6, ['*'], 'project_page')
                : Project::with('mahasiswa')->latest()->paginate(6, ['*'], 'project_page');
            $data->transform(fn($item) => tap($item, fn($i) => $i->type = 'project'));
            return view('partials.project_group', ['project' => $data]);

        } elseif ($group === 'sertifikat') {
            $data = $isDashboardMe
                ? Sertifikat::with('mahasiswa')->where('id_mahasiswa', $user->id)->where('is_active', true)->where('status_pengajuan', 'Di Terima')->latest()->paginate(6, ['*'], 'sertifikat_page')
                : Sertifikat::with('mahasiswa')->where('is_active', true)->where('status_pengajuan', 'Di Terima')->latest()->paginate(6, ['*'], 'sertifikat_page');
            $data->transform(fn($item) => tap($item, fn($i) => $i->type = 'sertifikat'));
            return view('partials.sertifikat_group', ['sertifikat' => $data]);
        }

        return response('Not found', 404);
    }

    public function show(Request $request)
    {
        $userParam = $request->query('user');
        if (!$userParam) abort(404, 'User parameter is required');

        $locale = request()->route('locale');
        if ($locale && in_array($locale, ['id', 'en'])) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        $user = User::where('id', $userParam)->orWhere('username', $userParam)->first();
        if (!$user) abort(404);

        if (is_numeric($userParam)) {
            return redirect($request->url() . '?user=' . $user->username);
        }

        if (in_array($user->role, ['admin', 'dosen'])) {
            return redirect()->back()->with('error', 'Halaman portofolio admin atau dosen tidak diperbolehkan dibuka.');
        }

        if (($user->status_pengajuan ?? '') !== 'Di Terima') {
            return redirect()->back()->with('error', 'Portofolio belum disetujui, akses tidak diizinkan.');
        }

        $user->load([
            'jurusan', 'angkatan', 'keahlian',
            'keahlianTambahan' => fn($q) => $q->wherePivot('status_pengajuan', 'Di Terima'),
            'postingans'       => fn($q) => $q->latest(),
            'sertifikats'      => fn($q) => $q->where('is_active', true)->where('status_pengajuan', 'Di Terima'),
            'learning_corners',
        ]);

        $isOwner    = Auth::check() && Auth::id() === $user->id;
        $projectTab = $request->get('project_tab', 'completed');
        $today      = Carbon::today();

        $postingans = $user->postingans()->latest()->paginate(3)->fragment('postingan-section');

        $projectsQuery = Project::with(['owner', 'leader', 'members'])
            ->where(function ($q) use ($user) {
                $q->where('id_mahasiswa', $user->id)
                  ->orWhere('leader_id', $user->id)
                  ->orWhereHas('members', fn($qq) => $qq->where('users.id', $user->id));
            });

        match ($projectTab) {
            'upcoming'  => $projectsQuery->where('tanggal_mulai', '>', $today),
            'completed' => $projectsQuery->whereNotNull('tanggal_akhir')->where('tanggal_akhir', '<', $today),
            default     => $projectsQuery->where('tanggal_mulai', '<=', $today)
                                         ->where(fn($q) => $q->where('tanggal_akhir', '>=', $today)->orWhereNull('tanggal_akhir')),
        };

        $projects = $projectsQuery->latest()->paginate(5)->withQueryString()->fragment('project-section');

        return view('views_portofolio_user', compact('user', 'postingans', 'projects', 'projectTab', 'isOwner'));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  SEARCH
    // ══════════════════════════════════════════════════════════════════════════
    public function search(Request $request)
    {
        $keyword  = trim($request->q ?? '');
        $jurusan  = $request->jurusan;
        $keahlian = $request->keahlian;
        $angkatan = $request->angkatan;
        $type     = $request->type;

        // ── Default semua ke collect() agar tidak undefined ──────────────────
        $results     = collect();
        $users       = collect();   // alias → $mahasiswa di view
        $projects    = collect();
        $sertifikats = collect();
        $postingan   = collect();

        // ════════════════════════
        //  POSTINGAN
        // ════════════════════════
        if (!$type || $type === 'postingan') {
            $q = Postingan::with(['user', 'komentar', 'likes', 'game']);

            if ($keyword !== '') {
                // Content disimpan sebagai JSON array of objects {type, content}
                // Cari di seluruh string JSON (paling kompatibel untuk semua DB)
                $q->where('content', 'like', "%{$keyword}%");
            }

            $postingan = $q->latest()
                ->paginate(9)
                ->withQueryString()
                ->fragment('postingan-section');
        }

        // ════════════════════════
        //  MAHASISWA
        // ════════════════════════
        if (!$type || $type === 'mahasiswa') {
            $q = User::with([
                    'jurusan', 'keahlian', 'angkatan',
                    'keahlianTambahan' => fn($qq) => $qq->where('keahlian_tambahan.status_pengajuan', 'Di Terima'),
                ])
                ->withCount([
                    'projects',
                    'leadingProjects',
                    'memberProjects',
                    'learning_corners as learning_count',
                    'sertifikats as sertifikats_count',
                ])
                ->where('role', 'mahasiswa')
                ->where('status_pengajuan', 'Di Terima');

            if ($keyword !== '') {
                $q->where('nama_mahasiswa', 'like', "%{$keyword}%");
            }

            if ($jurusan)  $q->where('id_jurusan', $jurusan);
            if ($angkatan) $q->where('id_angkatan', $angkatan);

            if ($keahlian) {
                $q->where(function ($query) use ($keahlian) {
                    $query->where('id_keahlian', $keahlian)
                          ->orWhereHas('keahlianTambahan', function ($qq) use ($keahlian) {
                              $qq->where('keahlian.id_keahlian', $keahlian)
                                 ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                          });
                });
            }

            $users = $q->paginate(9)->withQueryString()->fragment('mahasiswa-section');

            $users->getCollection()->transform(function ($item) {
                $item->project_total_count = Project::where(function ($q) use ($item) {
                    $q->where('id_mahasiswa', $item->id)
                      ->orWhere('leader_id', $item->id)
                      ->orWhereHas('members', fn($qq) => $qq->where('user_id', $item->id));
                })->count();
                $item->type = 'mahasiswa';
                return $item;
            });

            $results = $results->concat($users->getCollection());
        }

        // ════════════════════════
        //  PROJECT
        // ════════════════════════
        if (!$type || $type === 'project') {
            $q = Project::with(['mahasiswa.jurusan', 'mahasiswa.keahlian', 'mahasiswa.angkatan'])
                ->whereHas('mahasiswa', fn($qq) => $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'));

            if ($keyword !== '') {
                // isi_content adalah JSON — cari dengan LIKE pada kolom raw agar kompatibel
                $q->where('isi_content', 'like', "%{$keyword}%");
            }

            if ($jurusan)  $q->whereHas('mahasiswa', fn($qq) => $qq->where('id_jurusan', $jurusan));
            if ($angkatan) $q->whereHas('mahasiswa', fn($qq) => $qq->where('id_angkatan', $angkatan));

            if ($keahlian) {
                $q->whereHas('mahasiswa', function ($query) use ($keahlian) {
                    $query->where(function ($qq) use ($keahlian) {
                        $qq->where('id_keahlian', $keahlian)
                           ->orWhereHas('keahlianTambahan', function ($qqq) use ($keahlian) {
                               $qqq->where('keahlian.id_keahlian', $keahlian)
                                   ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                           });
                    });
                });
            }

            $projects = $q->latest()->paginate(9)->withQueryString()->fragment('projects-section');

            $projects->getCollection()->transform(function ($project) {
                // isi_content bisa berupa array (sudah di-cast) atau JSON string
                $content = is_array($project->isi_content)
                    ? $project->isi_content
                    : (json_decode($project->isi_content, true) ?? []);

                $project->nama_project = $content['nama_project'] ?? null;
                $project->link_project = $content['link_project'] ?? null;
                $project->link_github  = $content['link_github']  ?? null;
                $project->link_video   = $content['link_video']   ?? null;
                $project->type         = 'project';
                return $project;
            });

            $results = $results->concat($projects->getCollection());
        }

        // ════════════════════════
        //  SERTIFIKAT
        // ════════════════════════
        if (!$type || $type === 'sertifikat') {
            $q = Sertifikat::with(['mahasiswa.jurusan', 'mahasiswa.keahlian', 'mahasiswa.angkatan'])
                ->where('is_active', true)
                ->where('status_pengajuan', 'Di Terima')
                ->whereHas('mahasiswa', fn($qq) => $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'));

            if ($keyword !== '') {
                $q->where(function ($query) use ($keyword) {
                    $query->where('nama_sertifikat', 'like', "%{$keyword}%")
                          ->orWhere('lembaga_penerbit', 'like', "%{$keyword}%");
                });
            }

            if ($jurusan)  $q->whereHas('mahasiswa', fn($qq) => $qq->where('id_jurusan', $jurusan));
            if ($angkatan) $q->whereHas('mahasiswa', fn($qq) => $qq->where('id_angkatan', $angkatan));

            if ($keahlian) {
                $q->whereHas('mahasiswa', function ($query) use ($keahlian) {
                    $query->where(function ($qq) use ($keahlian) {
                        $qq->where('id_keahlian', $keahlian)
                           ->orWhereHas('keahlianTambahan', function ($qqq) use ($keahlian) {
                               $qqq->where('keahlian.id_keahlian', $keahlian)
                                   ->where('keahlian_tambahan.status_pengajuan', 'Di Terima');
                           });
                    });
                });
            }

            $sertifikats = $q->latest()->paginate(9)->withQueryString()->fragment('sertifikat-section');

            $sertifikats->getCollection()->transform(function ($item) {
                $item->type = 'sertifikat';
                return $item;
            });

            $results = $results->concat($sertifikats->getCollection());
        }

        // ════════════════════════
        //  TOTAL COUNT (statistik header)
        // ════════════════════════
        $totalMahasiswa = User::where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')->count();

        $totalLearning = LearningCorner::whereHas('mahasiswa',
            fn($q) => $q->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima')
        )->count();

        $totalProject = Project::where(function ($q) {
            $q->whereHas('mahasiswa', fn($qq) => $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'))
              ->orWhereHas('leader',   fn($qq) => $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'))
              ->orWhereHas('members',  fn($qq) => $qq->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'));
        })->count();

        $totalSertifikat = Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->whereHas('mahasiswa', fn($q) => $q->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'))
            ->count();

        // $mahasiswa = alias $users agar nama variabel di view tetap $mahasiswa
        $mahasiswa = $users;

        $hasResults = $mahasiswa->count()   > 0
                   || $projects->count()    > 0
                   || $sertifikats->count() > 0
                   || $postingan->count()   > 0;

        return view('views_result_search', compact(
            'results',
            'mahasiswa',
            'projects',
            'sertifikats',
            'postingan',        // ← PERBAIKAN: sekarang dikirim ke view
            'keyword',
            'totalMahasiswa',
            'totalLearning',
            'totalProject',
            'totalSertifikat',
            'hasResults'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  SEARCH SUGGESTIONS (autocomplete)
    // ══════════════════════════════════════════════════════════════════════════
    public function searchSuggestions(Request $request)
    {
        $keyword = $request->query('q');
        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        // Mahasiswa
        User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima')
            ->where('nama_mahasiswa', 'like', "%{$keyword}%")
            ->limit(5)
            ->get(['id', 'nama_mahasiswa', 'username'])
            ->each(function ($user) use (&$suggestions) {
                $suggestions[] = [
                    'type'  => 'mahasiswa',
                    'id'    => $user->id,
                    'name'  => $user->nama_mahasiswa,
                    'url'   => route('portfolio.show', ['user' => $user->username]),
                    'label' => 'Mahasiswa',
                ];
            });

        // Project
        Project::with('mahasiswa')
            ->where('isi_content', 'like', "%{$keyword}%")
            ->whereHas('mahasiswa', fn($q) => $q->where('role', 'mahasiswa')->where('status_pengajuan', 'Di Terima'))
            ->limit(5)
            ->get()
            ->each(function ($project) use (&$suggestions) {
                $content = is_array($project->isi_content)
                    ? $project->isi_content
                    : (json_decode($project->isi_content, true) ?? []);
                $suggestions[] = [
                    'type'  => 'project',
                    'id'    => $project->id,
                    'name'  => $content['nama_project'] ?? 'Project tanpa judul',
                    'url'   => route('project.show', ['id' => $project->id]),
                    'label' => 'Project',
                ];
            });

        // Sertifikat
        Sertifikat::where('is_active', true)
            ->where('status_pengajuan', 'Di Terima')
            ->where('nama_sertifikat', 'like', "%{$keyword}%")
            ->limit(5)
            ->get()
            ->each(function ($sertifikat) use (&$suggestions) {
                $suggestions[] = [
                    'type'  => 'sertifikat',
                    'id'    => $sertifikat->id,
                    'name'  => $sertifikat->nama_sertifikat,
                    'url'   => '#',
                    'label' => 'Sertifikat',
                ];
            });

        // Postingan
        Postingan::with('user')
            ->where('content', 'like', "%{$keyword}%")
            ->limit(10)
            ->get()
            ->each(function ($postingan) use (&$suggestions, $keyword) {
                $content = $postingan->content ?? [];
                $title   = collect($content)->firstWhere('type', 'title')['content'] ?? null;

                if ($title && str_contains(strtolower($title), strtolower($keyword))) {
                    $suggestions[] = [
                        'type'  => 'postingan',
                        'id'    => $postingan->id_postingan,
                        'name'  => $title,
                        'url'   => route('postingan.show', ['locale' => app()->getLocale(), 'id' => $postingan->id_postingan]),
                        'label' => 'Postingan',
                    ];
                }
            });

        return response()->json($suggestions);
    }
}