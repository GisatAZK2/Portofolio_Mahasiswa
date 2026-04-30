<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectTask;
use App\Models\LearningCorner;
use App\Models\Angkatan;
use App\Models\Jurusan;
use App\Models\Keahlian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    protected function userIsProjectManager(Project $project): bool
    {
        $userId = Auth::id();
        return $project->id_mahasiswa === $userId || $project->leader_id === $userId;
    }

    protected function userIsProjectParticipant(Project $project, int $userId): bool
    {
        if ($project->id_mahasiswa === $userId || $project->leader_id === $userId) {
            return true;
        }

        return $project->members()->where('user_id', $userId)->exists();
    }

    protected function authorizeProjectManager(Project $project): void
    {
        if (!$this->userIsProjectManager($project)) {
            abort(403, 'Akses hanya untuk owner atau leader project.');
        }
    }

    public function storeTask(Request $request, $projectId)
    {
        $project = Project::with('members')->findOrFail($projectId);
        $this->authorizeProjectManager($project);

        $validated = $request->validate([
            'name_task' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        if (!$this->userIsProjectParticipant($project, $validated['user_id'])) {
            return back()->withInput()->withErrors(['user_id' => 'User harus menjadi owner, leader, atau member project.']);
        }

        ProjectTask::create([
            'project_id' => $project->id,
            'name_task' => $validated['name_task'],
            'is_done' => false,
            'user_id' => $validated['user_id'],
        ]);

        return back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function updateTask(Request $request, $projectId, $taskId)
    {
        $project = Project::with('members')->findOrFail($projectId);
        $task = ProjectTask::where('project_id', $projectId)->findOrFail($taskId);
        $userId = Auth::id();

        if ($this->userIsProjectManager($project)) {
            $validated = $request->validate([
                'name_task' => 'sometimes|required|string|max:255',
                'user_id' => 'sometimes|required|exists:users,id',
                'is_done' => 'sometimes|boolean',
            ]);

            if (isset($validated['user_id']) && !$this->userIsProjectParticipant($project, $validated['user_id'])) {
                return back()->withInput()->withErrors(['user_id' => 'User harus menjadi owner, leader, atau member project.']);
            }

            $task->fill(array_filter($validated, fn($value) => $value !== null));
            $task->save();
        } elseif ($task->user_id === $userId) {
            $validated = $request->validate([
                'is_done' => 'required|boolean',
            ]);

            $task->is_done = $validated['is_done'];
            $task->save();
        } else {
            abort(403, 'Anda tidak dapat mengubah tugas ini.');
        }

        return back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function completeTask($projectId, $taskId)
    {
        $project = Project::with('members')->findOrFail($projectId);
        $task = ProjectTask::where('project_id', $projectId)->findOrFail($taskId);
        $userId = Auth::id();

        if (!$this->userIsProjectManager($project) && $task->user_id !== $userId) {
            abort(403, 'Anda tidak dapat menyelesaikan tugas ini.');
        }

        $task->is_done = true;
        $task->save();

        return back()->with('success', 'Tugas berhasil diselesaikan.');
    }

    public function destroyTask($projectId, $taskId)
    {
        $project = Project::with('members')->findOrFail($projectId);
        $task = ProjectTask::where('project_id', $projectId)->findOrFail($taskId);
        $userId = Auth::id();

        if (!$this->userIsProjectManager($project) && $task->user_id !== $userId) {
            abort(403, 'Anda tidak dapat menghapus tugas ini.');
        }

        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function project_user()
    {
        $projects = Project::with(['mahasiswa', 'leader'])->latest()->paginate(12);
        return view('project.views_project_user', compact('projects'));
    }

    // FORM TAMBAH
    public function create(Request $request)
{
    $search    = $request->query('search', '');
    $angkatan  = $request->query('angkatan', '');
    $jurusan   = $request->query('jurusan', '');
    $keahlian  = $request->query('keahlian', '');
    $page      = $request->query('page', 1);

    $authUser = auth()->user();
    $authId   = $authUser->id;

    $authAngkatan = $authUser->id_angkatan;
    $authJurusan  = $authUser->id_jurusan;
    $authKeahlian = $authUser->id_keahlian;

    $usersQuery = User::with(['angkatan', 'jurusan', 'keahlian'])
        ->select(
            'id',
            'nama_mahasiswa',
            'photo_profile',
            'email',
            'id_angkatan',
            'id_jurusan',
            'id_keahlian'
        )
        ->where('role', 'mahasiswa')
        ->where('is_active', 1)
        ->where('status_pengajuan', 'Di Terima')
        ->where('id', '!=', $authId);

    if (!empty($search)) {
        $usersQuery->where('nama_mahasiswa', 'like', '%' . $search . '%');
    }

    if (!empty($angkatan)) {
        $usersQuery->where('id_angkatan', $angkatan);
    }

    if (!empty($jurusan)) {
        $usersQuery->where('id_jurusan', $jurusan);
    }

    if (!empty($keahlian)) {
        $usersQuery->where('id_keahlian', $keahlian);
    }

    $usersQuery->orderByRaw("
        CASE
            WHEN id_angkatan = ? 
             AND id_jurusan = ? 
             AND id_keahlian = ? THEN 1

            WHEN id_angkatan = ? 
             AND id_jurusan = ? THEN 2

            ELSE 3
        END
    ", [
        $authAngkatan,
        $authJurusan,
        $authKeahlian,
        $authAngkatan,
        $authJurusan
    ])
    ->orderBy('nama_mahasiswa', 'asc');

    // Untuk AJAX request dengan pagination
    if ($request->ajax()) {
        $perPage = 10;
        $users = $usersQuery->paginate($perPage, ['*'], 'page', $page);
        
        $paginationHtml = '';
        if ($users->hasPages()) {
            $paginationHtml = view('vendor.pagination.custom_ajax', [
                'paginator' => $users,
                'elements' => $users->links()->elements,
                'groupName' => 'user-modal-pagination'
            ])->render();
        }
        
        $userListHtml = '';

        if ($users->count() > 0) {
            foreach ($users as $user) {
                $userListHtml .= '
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2" data-user-id="' . $user->id . '">
                    <div class="flex items-center gap-3">
                        ' . ($user->photo_profile
                    ? '<img src="/storage/' . $user->photo_profile . '" class="w-10 h-10 rounded-full object-cover">'
                    : '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">'
                    . strtoupper(substr($user->nama_mahasiswa, 0, 1)) .
                    '</span>
                       </div>'
                ) . '
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">'
                    . e($user->nama_mahasiswa) .
                    '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none"
                                 title="' . e($user->email) . '">'
                    . e($user->email) .
                    '</div>
                        </div>
                    </div>
                    <div>
                        <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm"
                            data-user-id="' . $user->id . '"
                            onchange="updateUserRole(this, ' . $user->id . ', this.value)">
                            <option value="" data-translate="choose_role" data-translate-page="dosen_add_pjt">-- Pilih Role --</option>
                            <option value="leader" data-translate="leader_role" data-translate-page="dosen_add_pjt">Leader</option>
                            <option value="member" data-translate="member_role" data-translate-page="dosen_add_pjt">Member</option>
                        </select>
                    </div>
                </div>';
            }
        } else {
            $userListHtml = '
            <div class="text-center py-10 text-gray-500 dark:text-gray-400" data-translate="no_students_found" data-translate-page="dosen_add_pjt">
                Tidak ada mahasiswa yang sesuai filter.
            </div>';
        }

        return response()->json([
            'userListHtml'   => $userListHtml,
            'paginationHtml' => $paginationHtml,
            'currentPage'    => $users->currentPage(),
            'lastPage'       => $users->lastPage(),
            'total'          => $users->total(),
        ]);
    }

    // Untuk non-AJAX (initial load)
    $angkatanList = Angkatan::orderBy('tahun_masuk', 'desc')->get();
    $jurusanList  = Jurusan::orderBy('nama_jurusan')->get();
    $keahlianList = Keahlian::orderBy('nama_keahlian')->get();
    
    $users = $usersQuery->paginate(10);

    return view('project.views_create_project', compact(
        'users',
        'search',
        'angkatan',
        'jurusan',
        'keahlian',
        'angkatanList',
        'jurusanList',
        'keahlianList'
    ));
}
    protected function createProjectTasks(Project $project, array $tasks, bool $skipValidation = false)
    {
        $allowedUsers = collect();
        $savedTaskIds = [];

        if (!$skipValidation) {
            $allowedUsers = collect([$project->id_mahasiswa, $project->leader_id])
                ->merge($project->members()->pluck('project_user.user_id'))
                ->filter()
                ->unique();
        }

        foreach ($tasks as $task) {
            $taskId = $task['id'] ?? null;
            $userId = $task['user_id'] ?? null;
            $name = trim($task['name_task'] ?? '');

            if (empty($userId) || empty($name)) {
                continue;
            }

            if (!$skipValidation && !$allowedUsers->contains($userId)) {
                continue;
            }

            if ($taskId) {
                $existingTask = ProjectTask::where('project_id', $project->id)
                    ->where('id', $taskId)
                    ->first();

                if ($existingTask) {
                    $existingTask->update([
                        'user_id' => $userId,
                        'name_task' => $name,
                    ]);
                    $savedTaskIds[] = $existingTask->id;
                    continue;
                }
            }

            $newTask = ProjectTask::create([
                'project_id' => $project->id,
                'user_id' => $userId,
                'name_task' => $name,
                'is_done' => false,
            ]);

            $savedTaskIds[] = $newTask->id;
        }

        return $savedTaskIds;
    }
    // SIMPAN BARU
    public function store(Request $request)
    {
        // Filter tasks yang incomplete
        $filteredTasks = collect($request->input('tasks', []))
            ->filter(function ($task) {
                if (!is_array($task))
                    return false;
                $userId = trim($task['user_id'] ?? '');
                $nameTask = trim($task['name_task'] ?? '');
                return !empty($userId) && !empty($nameTask);
            })
            ->values()
            ->all();

        $request->merge(['tasks' => $filteredTasks]);

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id|different:leader',
            'tasks' => 'nullable|array',
            'tasks.*.user_id' => 'required|exists:users,id',
            'tasks.*.name_task' => 'required|string|max:255',
        ]);

        $content = array_filter($request->only([
            'nama_project',
            'judul',
            'deskripsi',
            'link_project',
            'link_github',
            'link_video'
        ]), fn($value) => !is_null($value) && $value !== '');

        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field']);
        }

        $project = Project::create([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => Auth::id(),
            'leader_id' => $request->leader,
        ]);

        // Attach members
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($id) => $id == $request->leader)
            ->map(fn($id) => (int) $id)
            ->all();

        if (!empty($members)) {
            $project->members()->attach($members);
        }

        // Create tasks
        if (!empty($request->tasks)) {
            $this->createProjectTasks($project, $request->tasks, true);
        }

        // Kirim notifikasi
        $this->createProjectNotifications($project, $members);

        return redirect()->route('project.index')
            ->with('success', 'Project berhasil ditambahkan!');
    }

    private function createProjectNotifications($project, $members)
    {
        $user = Auth::user();
        $projectName = $project->isi_content['nama_project'] ?? 'Tanpa Nama';
        $userName = $user->nama_mahasiswa ?? $user->username ?? 'User';

        /*
        |--------------------------------------------------------------------------
        | 1. Notifikasi ke Admin
        |--------------------------------------------------------------------------
        */
        \App\Http\Controllers\v1\NotificationController::add(
            'project-created',
            [
                'title' => 'Project Baru Dibuat',
                'message' => "{$userName} membuat project: {$projectName}" .
                    (count($members) > 0 ? " dengan " . count($members) . " anggota" : ""),
                'user_id' => $user->id,
                'user_name' => $userName,
                'project_id' => $project->id,
                'project_name' => $projectName,
                'members_count' => count($members),
                'link' => url(app()->getLocale() . '/projectUser?id=' . $project->id),
            ],
            'high'
        );

        /*
        |--------------------------------------------------------------------------
        | 2. Kumpulkan penerima (leader + members)
        |--------------------------------------------------------------------------
        */
        $receivers = collect($members);

        if (!empty($project->leader_id)) {
            $receivers->push($project->leader_id);
        }

        $receivers = $receivers
            ->unique()
            ->filter(fn($id) => $id != $user->id) // jangan kirim ke pembuat sendiri
            ->values();

        foreach ($receivers as $receiverId) {
            \App\Http\Controllers\v1\NotificationController::add(
                'project-assigned',
                [
                    'title' => 'Anda Ditambahkan ke Project',
                    'message' => "{$userName} menambahkan Anda ke project: {$projectName}",
                    "target_type" => "specific",
                    "selected_users" => $receiverId,
                    'sender_id' => $user->id,
                    'sender_name' => $userName,
                    'project_id' => $project->id,
                    'project_name' => $projectName,
                    'link' => url(app()->getLocale() . '/projectUser?id=' . $project->id),
                ],
                'normal'
            );
        }
    }

    // Ubah method edit untuk menerima query parameter 'id' dan 'user'
    public function edit(Request $request)
{
    $id = $request->query('id');
    $username = $request->query('user');

    if (!$id) {
        abort(404, 'Project ID is required');
    }

    // ================= USER VALIDATION =================
    $user = null;

    if ($username) {
        $user = User::where('username', $username)->firstOrFail();

        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
    } else {
        $user = Auth::user();
    }

    // ================= PROJECT =================
    $project = Project::with(['members', 'leader', 'mahasiswa', 'tasks'])
        ->where('id', $id)
        ->firstOrFail();

    if (Auth::user()->role !== 'admin' && $project->id_mahasiswa !== $user->id) {
        abort(403, 'You can only edit your own projects');
    }

    // ================= FILTER =================
    $search    = $request->query('search', '');
    $angkatan  = $request->query('angkatan', '');
    $jurusan   = $request->query('jurusan', '');
    $keahlian  = $request->query('keahlian', '');

    $authId = auth()->id();

    $query = User::with(['angkatan', 'jurusan', 'keahlian'])
        ->select(
            'id',
            'nama_mahasiswa',
            'photo_profile',
            'email',
            'id_angkatan',
            'id_jurusan',
            'id_keahlian'
        )
        ->whereNotIn('role', ['admin', 'dosen'])
        ->where('is_active', 1)
        ->where('status_pengajuan', 'Di Terima')
        ->where('id', '!=', $authId);

    if (!empty($search)) {
        $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
    }

    if (!empty($angkatan)) {
        $query->where('id_angkatan', $angkatan);
    }

    if (!empty($jurusan)) {
        $query->where('id_jurusan', $jurusan);
    }

    if (!empty($keahlian)) {
        $query->where('id_keahlian', $keahlian);
    }

    $users = $query->paginate(10);

    // ==================================================
    // AJAX REQUEST
    // ==================================================
    if ($request->ajax()) {

        $leaderId = optional($project->leader)->id;
        $memberIds = $project->members->pluck('id')->toArray();

        $userListHtml = '';

        if ($users->count() > 0) {
            foreach ($users as $u) {
                $selectedRole = '';

                if ($leaderId == $u->id) {
                    $selectedRole = 'leader';
                } elseif (in_array($u->id, $memberIds)) {
                    $selectedRole = 'member';
                }

                $userListHtml .= '
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2" data-user-id="' . $u->id . '">
                    <div class="flex items-center gap-3">
                        ' . ($u->photo_profile
                    ? '<img src="/storage/' . $u->photo_profile . '" class="w-10 h-10 rounded-full object-cover">'
                    : '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">'
                    . strtoupper(substr($u->nama_mahasiswa, 0, 1)) .
                    '</span>
                       </div>'
                ) . '
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">'
                    . e($u->nama_mahasiswa) .
                    '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none"
                                 title="' . e($u->email) . '">'
                    . e($u->email) .
                    '</div>
                        </div>
                    </div>
                    <div>
                        <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm"
                            data-user-id="' . $u->id . '">
                            <option value="" data-translate="choose_role" data-translate-page="dosen_add_pjt">-- Pilih Role --</option>
                            <option value="leader" data-translate="leader_role" data-translate-page="dosen_add_pjt"' . ($selectedRole == 'leader' ? 'selected' : '') . '>Leader</option>
                            <option value="member" data-translate="member_role" data-translate-page="dosen_add_pjt"' . ($selectedRole == 'member' ? 'selected' : '') . '>Member</option>
                        </select>
                    </div>
                </div>';
            }
        } else {
            $userListHtml = '
            <div class="text-center py-10 text-gray-500 dark:text-gray-400" data-translate="no_students_found" data-translate-page="dosen_add_pjt">
                Tidak ada mahasiswa yang sesuai filter.
            </div>';
        }

        $paginationHtml = $users->render(
            'vendor.pagination.custom_ajax',
            ['groupName' => 'admin_project_user_selection']
        )->toHtml();

        return response()->json([
            'userListHtml'   => $userListHtml,
            'paginationHtml' => $paginationHtml,
            'currentPage'    => $users->currentPage(),
            'lastPage'       => $users->lastPage(),
        ]);
    }

    // ==================================================
    // NORMAL VIEW
    // ==================================================
    $angkatans = Angkatan::all();
    $jurusans = Jurusan::all();
    $keahlians = Keahlian::all();

    return view('project.views_edit_project', compact(
        'project',
        'users',
        'angkatans',
        'jurusans',
        'keahlians',
        'search',
        'angkatan',
        'jurusan',
        'keahlian'
    ));
}

    // Update method update juga
    public function update(Request $request)
    {
        $id = $request->query('id');
        $username = $request->query('user');

        if (!$id) {
            abort(404, 'Project ID is required');
        }

        // Cek user berdasarkan username
        $user = null;
        if ($username) {
            $user = User::where('username', $username)->firstOrFail();

            if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access');
            }
        } else {
            $user = Auth::user();
        }

        $project = Project::where('id', $id)
            ->where('id_mahasiswa', $user->id)
            ->firstOrFail();

        // Rest of your update logic remains the same...
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id|different:leader',
            'tasks' => 'nullable|array',
            'tasks.*.id' => 'sometimes|nullable|integer|exists:project_tasks,id',
            'tasks.*.user_id' => 'sometimes|nullable|exists:users,id',
            'tasks.*.name_task' => 'sometimes|nullable|string|max:255',
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

        $oldMembers = $project->members()->pluck('user_id')->toArray();
        $oldLeader = $project->leader_id;

        $oldReceivers = collect($oldMembers)
            ->push($oldLeader)
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Update project
        $project->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'leader_id' => $request->leader,
        ]);

        // Handle members
        $members = collect($request->input('members', []))
            ->filter()
            ->reject(fn($memberId) => $memberId == $request->leader)
            ->unique()
            ->values()
            ->all();

        $project->members()->sync($members);

        $newReceivers = collect($members)
            ->push($request->leader)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->updateProjectNotifications($project, $oldReceivers, $newReceivers);

        $allowedUserIds = collect([$project->id_mahasiswa])
            ->when($project->leader_id, fn($collection, $leaderId) => $collection->push($leaderId))
            ->merge($members)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $submittedTasks = collect($request->input('tasks', []))
            ->map(function ($task) {
                return [
                    'id' => $task['id'] ?? null,
                    'user_id' => $task['user_id'] ?? null,
                    'name_task' => trim($task['name_task'] ?? ''),
                ];
            })
            ->filter(function ($task) {
                return !empty($task['user_id']) && !empty($task['name_task']);
            })
            ->values()
            ->all();

        $savedTaskIds = $this->createProjectTasks($project, $submittedTasks, false);

        if (!empty($savedTaskIds)) {
            ProjectTask::where('project_id', $project->id)
                ->whereNotIn('id', $savedTaskIds)
                ->delete();
        } else {
            ProjectTask::where('project_id', $project->id)->delete();
        }

        ProjectTask::where('project_id', $project->id)
            ->whereNotIn('user_id', $allowedUserIds)
            ->delete();


        $locale = app()->getLocale();
        return redirect()->route('project.index', ['locale' => $locale])
            ->with('success', 'Project berhasil diperbarui!');
    }
    private function updateProjectNotifications($project, array $oldReceivers, array $newReceivers)
    {
        $user = Auth::user();

        $projectName = $project->isi_content['nama_project'] ?? 'Tanpa Nama';
        $userName = $user->nama_mahasiswa ?? $user->username ?? 'User';

        $oldReceivers = collect($oldReceivers)->filter()->unique()->values();
        $newReceivers = collect($newReceivers)->filter()->unique()->values();

        /*
        |--------------------------------------------------------------------------
        | 1. User baru ditambahkan
        |--------------------------------------------------------------------------
        */
        $addedUsers = $newReceivers->diff($oldReceivers);

        foreach ($addedUsers as $receiverId) {

            // cek kalau notif lama sudah ada, skip
            $exists = \App\Models\Notification::where('type', 'project-assigned')
                ->where('data->project_id', $project->id)
                ->where('data->selected_users', (int) $receiverId)
                ->exists();

            if (!$exists) {
                \App\Http\Controllers\v1\NotificationController::add(
                    'project-assigned',
                    [
                        'title' => 'Anda Ditambahkan ke Project',
                        'message' => "{$userName} menambahkan Anda ke project: {$projectName}",
                        'target_type' => 'specific',
                        'selected_users' => $receiverId,
                        'sender_id' => $user->id,
                        'sender_name' => $userName,
                        'project_id' => $project->id,
                        'project_name' => $projectName,
                        'link' => url(app()->getLocale() . '/projectUser?id=' . $project->id),
                    ],
                    'normal'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. User dihapus dari project
        |--------------------------------------------------------------------------
        */
        $removedUsers = $oldReceivers->diff($newReceivers);

        foreach ($removedUsers as $receiverId) {

            // hapus notif lama
            \App\Models\Notification::where('type', 'project-assigned')
                ->where('data->project_id', $project->id)
                ->where('data->selected_users', (int) $receiverId)
                ->delete();

            // kirim notif keluar
            \App\Http\Controllers\v1\NotificationController::add(
                'project-removed',
                [
                    'title' => 'Dikeluarkan dari Project',
                    'message' => "Anda telah dikeluarkan dari project: {$projectName}",
                    'target_type' => 'specific',
                    'selected_users' => $receiverId,
                    'sender_id' => $user->id,
                    'sender_name' => $userName,
                    'project_id' => $project->id,
                    'project_name' => $projectName,
                    'link' => url(app()->getLocale() . '/projectUser?id=' . $project->id),
                ],
                'high'
            );
        }
    }

    // Update destroy method juga
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        $username = $request->query('user');

        if (!$id) {
            abort(404, 'Project ID is required');
        }

        $user = null;
        if ($username) {
            $user = User::where('username', $username)->firstOrFail();
            if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access');
            }
        } else {
            $user = Auth::user();
        }

        $project = Project::where('id', $id)
            ->where('id_mahasiswa', $user->id)
            ->firstOrFail();

        $project->delete();

        $locale = app()->getLocale();
        return redirect()->route('project.index', ['locale' => $locale])
            ->with('success', 'Project berhasil dihapus!');
    }

    public function show(Request $request)
    {
        // Get ID from query parameter
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Project ID is required');
        }

        $project = Project::with(['leader', 'members', 'tasks', 'learningCorners'])
            ->findOrFail($id);

        $entries = LearningCorner::where('project_id', $project->id)
            ->latest()
            ->get();

        $user = Auth::user();
        $showTaskSection = false;
        $canSeeAllTasks = false;
        $visibleTasks = collect();

        if (Auth::check()) {
        $project->recordView(Auth::id());
        $project->refresh(); 
        }



        if ($user) {
            $isOwner = $project->id_mahasiswa === $user->id;
            $isLeader = $project->leader_id === $user->id;
            $isMember = $project->members->contains('id', $user->id);

            if ($user->role === 'admin') {
                $showTaskSection = true;
                $canSeeAllTasks = true;
                $visibleTasks = $project->tasks;
            } elseif ($user->role === 'dosen') {
                $showTaskSection = true;
                $canSeeAllTasks = false;
                $visibleTasks = $project->tasks->filter(function ($task) use ($user) {
                    if (!$task->user) {
                        return false;
                    }

                    return $task->user->id_angkatan === $user->id_angkatan
                        && $task->user->id_jurusan === $user->id_jurusan
                        && $task->user->id_keahlian === $user->id_keahlian;
                })->values();
            } else {
                $showTaskSection = $isOwner || $isLeader || $isMember;
                $canSeeAllTasks = $isOwner || $isLeader;

                if ($showTaskSection) {
                    $visibleTasks = $canSeeAllTasks
                        ? $project->tasks
                        : $project->tasks->where('user_id', $user->id)->values();
                }
            }
        }

        $today = Carbon::now();
        $start = Carbon::parse($project->tanggal_mulai);
        $end = $project->tanggal_akhir ? Carbon::parse($project->tanggal_akhir) : null;

        if ($end) {
            if ($today->lessThan($start)) {
                $status = 'incoming';
                $statusText = 'Akan Datang';
                $statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                $timelinePercent = 0;
            } elseif ($today->greaterThan($end)) {
                $status = 'past';
                $statusText = 'Selesai';
                $statusColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                $totalDays = max(1, $start->diffInDays($end));
                $daysPassed = min($totalDays, $start->diffInDays($today));
                $timelinePercent = round(($daysPassed / $totalDays) * 100);
            } else {
                $status = 'present';
                $statusText = 'Sedang Berjalan';
                $statusColor = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                $totalDays = max(1, $start->diffInDays($end));
                $daysPassed = max(0, min($totalDays, $start->diffInDays($today)));
                $timelinePercent = round(($daysPassed / $totalDays) * 100);
            }
        } else {
            if ($today->lessThan($start)) {
                $status = 'incoming';
                $statusText = 'Akan Datang';
                $statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                $timelinePercent = 0;
            } else {
                $status = 'present';
                $statusText = 'Sedang Berjalan';
                $statusColor = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                $timelinePercent = 100;
            }
        }

        $taskTotalCount = $project->tasks->count();
        $taskDoneCount = $project->tasks->where('is_done', true)->count();

        if ($taskTotalCount === 0 || $taskDoneCount === 0) {
            // Kalau tidak ada task atau tidak ada task yang selesai,
            // progress tetap 0% meskipun timeline sudah berjalan.
            $projectProgress = 0;
        } else {
            $timelineContribution = min(10, max(0, $timelinePercent * 0.1));
            $taskRatio = ($taskDoneCount / $taskTotalCount) * 100;
            $taskContribution = round($taskRatio * 0.9);
            $projectProgress = min(100, round($timelineContribution + $taskContribution));
        }

        return view('project.views_detail_project', compact(
            'project',
            'entries',
            'showTaskSection',
            'canSeeAllTasks',
            'visibleTasks',
            'projectProgress',
            'taskTotalCount',
            'taskDoneCount',
            'status',
            'statusText',
            'statusColor'
        ));
    }


}