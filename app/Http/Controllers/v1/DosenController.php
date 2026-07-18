<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Sertifikat;
use App\Models\Keahlian;
use App\Models\Jurusan;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'dosen') {
            abort(Response::HTTP_FORBIDDEN, 'Akses hanya untuk dosenistrator.');
        }
    }


    private function createProjectTasks(Project $project, array $tasks)
    {
        $project = $project->fresh(['members']);

        $allowedUserIds = collect();

        if (!empty($project->id_mahasiswa)) {
            $allowedUserIds->push((int) $project->id_mahasiswa);
        }

        if (!empty($project->leader_id)) {
            $allowedUserIds->push((int) $project->leader_id);
        }

        $allowedUserIds = $allowedUserIds
            ->merge(
                $project->members->pluck('id')->map(fn($id) => (int) $id)
            )
            ->filter()
            ->unique()
            ->values()
            ->all();

        $savedTaskIds = [];

        foreach ($tasks as $task) {

            if (!isset($task['user_id'], $task['name_task'])) {
                continue;
            }

            $taskId = isset($task['id']) ? (int) $task['id'] : null;
            $taskUserId = (int) $task['user_id'];
            $taskName = trim($task['name_task']);

            if ($taskUserId === 0 || $taskName === '') {
                continue;
            }

            if ($taskId) {
                $existingTask = ProjectTask::where('project_id', $project->id)
                    ->where('id', $taskId)
                    ->first();

                if ($existingTask) {
                    $existingTask->update([
                        'user_id' => $taskUserId,
                        'name_task' => $taskName,
                    ]);

                    $savedTaskIds[] = $existingTask->id;
                    continue;
                }
            }

            $newTask = ProjectTask::create([
                'project_id' => $project->id,
                'user_id' => $taskUserId,
                'name_task' => $taskName,
                'is_done' => false,
            ]);

            $savedTaskIds[] = $newTask->id;
        }

        return $savedTaskIds;
    }

    private function getdosenFilterScope($query)
    {
        $dosen = Auth::user();

        $query->where('status_pengajuan', 'Di Terima');

        if ($dosen->id_jurusan) {
            $query->where('id_jurusan', $dosen->id_jurusan);
        }

        return $query;
    }

    private function getdosenFilterScopeForRelated($query, $relation)
    {
        $dosen = Auth::user();

        $query->whereHas($relation, function ($q) use ($dosen) {
            $q->where('status_pengajuan', 'Di Terima');

            if ($dosen->id_jurusan) {
                $q->where('id_jurusan', $dosen->id_jurusan);
            }
        });

        return $query;
    }

    public function index()
    {
        $this->authorizeAccess();
        return view('dosen.index');
    }

    //For Pages User
    public function ListUser(Request $request)
    {
        $this->authorizeAccess();
        $dosen = Auth::user();

        // === SEARCH ===
        $query = User::query()
            ->whereIn('role', ['mahasiswa'])
            ->where('id', '!=', Auth::id())
            ->with(['jurusan', 'angkatan', 'keahlian'])
            ->withCount([
                'projects',
                'sertifikats',
                'learning_corners'
            ])
            ->orderBy('created_at', 'desc');
        $query = $this->getdosenFilterScope($query);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $angkatan = $request->input('angkatan', '');
        $jurusan = $request->input('jurusan', '');
        $keahlian = $request->input('keahlian', '');

        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        $users = $query->get();

        // Retrieve all Angkatan and Jurusan data
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        return view('dosen.daftar-mahasiswa', compact(
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

    // ============================================================
    // Method: ViewAddUser (replace yang lama)
    // ============================================================

    public function ViewAddUser()
    {
        $this->authorizeAccess();
        $dosen = Auth::user();

        // Data referensi sudah difilter sesuai dosen yang login
        // (tetap dikirim ke view untuk keperluan tampilan info)
        $jurusan  = $dosen->id_jurusan  ? Jurusan::where('id_jurusan', $dosen->id_jurusan)->get()   : collect();
        $keahlian = $dosen->id_keahlian ? Keahlian::where('id_keahlian', $dosen->id_keahlian)->get() : collect();
        $angkatan = $dosen->id_angkatan ? Angkatan::where('id', $dosen->id_angkatan)->get()           : collect();

        return view('dosen.user.views_add_user', compact('jurusan', 'keahlian', 'angkatan', 'dosen'));
    }

    // ============================================================
    // Method: StoreUser  ← ganti nama sesuai route yg sudah ada
    // (ini menggantikan AddUser lama, sekarang handle simple & full)
    // ============================================================

    public function AddUser(Request $request)
    {
        $this->authorizeAccess();
        $dosen = Auth::user();

        $registrationType = $request->input('registration_type', 'full');

        // ── Rules dasar ──────────────────────────────────────────
        $rules = [
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'photo_profile'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        if ($registrationType === 'simple') {
            // Registrasi Sederhana: hanya NIM + Nama + Tanggal Lahir (opsional)
            $rules['nim']           = ['required', 'string', 'max:20', 'unique:users,nim'];
            $rules['tanggal_lahir'] = ['nullable', 'date', 'before_or_equal:today'];
            $rules['jenis_kelamin'] = ['nullable', 'in:Laki-laki,Perempuan,Tidak ingin memberi tahu'];

        } else {
            // Registrasi Lengkap: semua field wajib
            $rules['nim']           = ['required', 'string', 'max:20', 'unique:users,nim'];
            $rules['username']      = [
                'required', 'string', 'max:100',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/',
            ];
            $rules['email']         = ['nullable', 'email', 'max:100', 'unique:users,email'];
            $rules['password']      = [
                'required',
                'confirmed',
                Password::min(8)->mixedCase(),
                'regex:/^\S+$/',
            ];
            $rules['jenis_kelamin'] = ['nullable', 'in:Laki-laki,Perempuan,Tidak ingin memberi tahu'];
            $rules['tanggal_lahir'] = ['required', 'date', 'before_or_equal:today'];
        }

        $validated = $request->validate($rules);

        // ── Foto profil ──────────────────────────────────────────
        $photoPath = null;
        if ($request->hasFile('photo_profile')) {
            $photoPath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        }

        // ── Buat user ────────────────────────────────────────────
        $userData = [
            'nama_mahasiswa'   => $validated['nama_mahasiswa'],
            'nim'              => $validated['nim'],
            'jenis_kelamin'    => $validated['jenis_kelamin'] ?? null,
            'photo_profile'    => $photoPath,
            'role'             => 'mahasiswa',
            // Data akademik otomatis dari dosen yang login
            'id_jurusan'       => $dosen->id_jurusan,
            'id_keahlian'      => $dosen->id_keahlian,
            'id_angkatan'      => $dosen->id_angkatan,
            'status_pengajuan' => 'Di Terima',
            'is_active'        => 1,
        ];

        if ($registrationType === 'simple') {
            // Password default = NIM
            $userData['password']      = Hash::make($validated['nim']);
            $userData['username']      = null;
            $userData['email']         = null;
            $userData['tanggal_lahir'] = $validated['tanggal_lahir'] ?? null;
        } else {
            $userData['username']      = $validated['username'];
            $userData['email']         = $validated['email'] ?? null;
            $userData['password']      = Hash::make($validated['password']);
            $userData['tanggal_lahir'] = $validated['tanggal_lahir'];
        }

        User::create($userData);

        return redirect()
            ->route('dosen.users.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    // ============================================================
    // Method: ImportExcel  (BARU - tambahkan ke controller)
    // ============================================================

    public function ImportExcel(Request $request)
    {
        $this->authorizeAccess();
        $dosen = Auth::user();

        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        try {
            $file = $request->file('excel_file');
            $data = $this->parseExcelFile($file);

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File Excel kosong atau format tidak sesuai.',
                ], 400);
            }

            $successCount = 0;
            $failedCount  = 0;
            $failedRows   = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $nim  = trim($row['nim']  ?? $row['NIM']  ?? '');
                    $nama = trim($row['nama'] ?? $row['Nama'] ?? $row['Nama Lengkap'] ?? '');
                    $tanggalLahir = $this->parseDate($row['tanggal_lahir'] ?? $row['Tanggal Lahir'] ?? '');

                    if (empty($nim) || empty($nama)) {
                        $failedCount++;
                        $failedRows[] = ['row' => $rowNumber, 'reason' => 'NIM atau Nama tidak boleh kosong'];
                        continue;
                    }

                    if (User::where('nim', $nim)->exists()) {
                        $failedCount++;
                        $failedRows[] = ['row' => $rowNumber, 'reason' => "NIM {$nim} sudah terdaftar"];
                        continue;
                    }

                    User::create([
                        'nim'              => $nim,
                        'nama_mahasiswa'   => $nama,
                        'username'         => null,
                        'email'            => null,
                        'password'         => Hash::make($nim), // password default = NIM
                        'tanggal_lahir'    => $tanggalLahir,
                        // Akademik otomatis dari dosen yang login
                        'id_jurusan'       => $dosen->id_jurusan,
                        'id_keahlian'      => $dosen->id_keahlian,
                        'id_angkatan'      => $dosen->id_angkatan,
                        'role'             => 'mahasiswa',
                        'status_pengajuan' => 'Di Terima',
                        'is_active'        => 1,
                        'photo_profile'    => null,
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $failedCount++;
                    $failedRows[] = ['row' => $rowNumber, 'reason' => 'Error: ' . $e->getMessage()];
                    \Log::error("Import dosen baris {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => "Import selesai: {$successCount} berhasil, {$failedCount} gagal.",
                'failedRows' => $failedRows,
                'stats'      => [
                    'success' => $successCount,
                    'failed'  => $failedCount,
                    'total'   => count($data),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Import Excel dosen error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // Helper: parseExcelFile  (sama seperti admin, tambahkan jika
    // belum ada di controller ini)
    // ============================================================

    private function parseExcelFile($file): array
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

            if (count($rows) < 2) return [];

            $rawHeaders = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);

            $headerAliases = [
                'nim'           => ['nim', 'n i m'],
                'Nama Lengkap'  => ['nama lengkap', 'nama', 'full name'],
                'Tanggal Lahir' => ['tanggal lahir', 'tgl lahir', 'birth date', 'birthdate'],
            ];

            $colMap = [];
            foreach ($rawHeaders as $colIndex => $rawHeader) {
                foreach ($headerAliases as $standardKey => $aliases) {
                    if (in_array($rawHeader, $aliases, true)) {
                        $colMap[$colIndex] = $standardKey;
                        break;
                    }
                }
            }

            $data = [];
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                $allEmpty = true;
                foreach ($row as $cell) {
                    if (!empty(trim((string) $cell))) { $allEmpty = false; break; }
                }
                if ($allEmpty) continue;

                $mapped = [];
                foreach ($colMap as $colIndex => $standardKey) {
                    $mapped[$standardKey] = isset($row[$colIndex]) ? trim((string) $row[$colIndex]) : '';
                }

                if (!empty($mapped['nim'] ?? '')) {
                    $data[] = $mapped;
                }
            }

            return $data;

        } catch (\Exception $e) {
            \Log::error('parseExcelFile dosen error: ' . $e->getMessage());
            return [];
        }
    }

    // ============================================================
    // Helper: parseDate  (tambahkan jika belum ada)
    // ============================================================

    private function parseDate($date): ?string
    {
        if (empty($date)) return null;

        if (is_numeric($date)) {
            $unix = ((int) $date - 25569) * 86400;
            return date('Y-m-d', $unix);
        }

        $date    = trim((string) $date);
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y', 'd/m/y', 'Y/m/d'];

        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed && $parsed->format($format) === $date) {
                return $parsed->format('Y-m-d');
            }
        }

        $ts = strtotime($date);
        return $ts !== false ? date('Y-m-d', $ts) : null;
    }
    // DetailsUser - ambil id dari query parameter
    public function DetailsUser(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'User ID is required');
        }

        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();
        $angkatans = Angkatan::all();

        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        $user = User::with(['jurusan', 'keahlian', 'angkatan'])
            ->where('id', $id);

        $user = $this->getdosenFilterScope($user)->firstOrFail();

        if ($user->id_keahlian != $dosen->id_keahlian) {
            return redirect()->route('dosen.users.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit mahasiswa dengan keahlian berbeda.');
        }

        return view('dosen.user.views_edit_user', compact(
            'user',
            'jurusans',
            'keahlians',
            'angkatans'
        ));
    }

    // UpdateUser - ambil id dari query parameter
   public function UpdateUser(Request $request)
{
    $this->authorizeAccess();
    $dosen = Auth::user();
    $id = $request->query('id');

    if (!$id) {
        return redirect()->route('dosen.users.index')
            ->with('error', 'User ID tidak ditemukan');
    }

    $user = User::where('id', $id);
    $user = $this->getdosenFilterScope($user)->firstOrFail();

    if ($user->id_keahlian != $dosen->id_keahlian) {
        return redirect()->route('dosen.users.index')
            ->with('error', 'Anda tidak memiliki akses untuk mengedit mahasiswa dengan keahlian berbeda.');
    }

    $rules = [
        'nama_mahasiswa' => ['sometimes', 'required', 'string', 'max:100'],
        'username' => [
            'sometimes',
            'required',
            'string',
            'max:100',
            'regex:/^[a-zA-Z0-9_]+$/',
            Rule::unique('users')->ignore($user->id)
        ],
        'email' => [
            'sometimes',
            'nullable',
            'email',
            'max:100',
            Rule::unique('users')->ignore($user->id)
        ],
        'nim' => [
            'sometimes',
            'nullable',
            Rule::requiredIf(function () use ($request, $user) {
                return ($request->has('role') ? $request->role : $user->role) === 'mahasiswa';
            }),
            'string',
            'max:20'
        ],
        'tanggal_lahir' => [
            'sometimes',
            'nullable',
            'date',
            'before_or_equal:today'
        ],
        'status_pengajuan' => [
            'sometimes',
            'required',
            'in:Di Terima,Di Tolak'
        ],
        'is_active' => [
            'sometimes',
            'required',
            'boolean'
        ],
        'password' => [
            'nullable',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[A-Z])\S*$/'
        ],
        'photo_profile' => [
            'sometimes',
            'nullable',
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048'
        ],
    ];

    $validated = $request->validate($rules);

    // Hanya update field yang dikirim
    $updateData = [];
    
    $allowedFields = [
        'nama_mahasiswa',
        'username',
        'email',
        'nim',
        'tanggal_lahir',
        'status_pengajuan',
        'is_active'
    ];

    foreach ($allowedFields as $field) {
        if ($request->has($field)) {
            $updateData[$field] = $validated[$field] ?? null;
        }
    }

    // Update password jika diisi
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    // Handle foto profil
    if ($request->hasFile('photo_profile')) {
        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        $photoPath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        $updateData['photo_profile'] = $photoPath;
    }

    $user->update($updateData);

    return redirect()
        ->route('dosen.users.index')
        ->with('success', 'User berhasil diperbarui.');
}

    // updateStatus - ambil id dari query parameter
    public function updateStatus(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('dosen.users.index')
                ->with('error', 'User ID tidak ditemukan');
        }

        $userQuery = User::where('id', $id);
        $user = $this->getdosenFilterScope($userQuery)->firstOrFail();

        $validated = $request->validate([
            'status_pengajuan' => 'required|in:Di Terima,Di Tolak',
            'keterangan_tolak' => 'required_if:status_pengajuan,Di Tolak|string|max:500',
        ]);

        $user->status_pengajuan = $validated['status_pengajuan'];

        if ($validated['status_pengajuan'] === 'Di Tolak') {
            $user->keterangan_tolak = $validated['keterangan_tolak'];
        } else {
            $user->keterangan_tolak = null;
        }

        $user->save();

        $nama = $user->nama_mahasiswa ?? $user->username;

        return redirect()
            ->route('dosen.users.index')
            ->with('success', "Status pengajuan {$nama} berhasil diupdate menjadi {$user->status_pengajuan}");
    }

    // destroyUser - ambil id dari query parameter
    public function destroyUser(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('dosen.users.index')
                ->with('error', 'User ID tidak ditemukan');
        }

        $userQuery = User::where('id', $id);
        $user = $this->getdosenFilterScope($userQuery)->firstOrFail();

        $dosen = Auth::user();
        if ($user->id_keahlian != $dosen->id_keahlian) {
            return redirect()->route('dosen.users.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus mahasiswa dengan keahlian berbeda.');
        }

        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
            Storage::disk('public')->delete($user->background_url);
        }

        $user->delete();

        return redirect()->route('dosen.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    //For Pages Sertifikat
    public function sertifikat(Request $request)
    {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');
        $status_pengajuan = $request->input('status_pengajuan');
        $selected_ids = $request->input('selected_ids', []);

        $query = Sertifikat::with('mahasiswa.jurusan', 'mahasiswa.angkatan', 'mahasiswa.keahlian');

        $query = $this->getdosenFilterScopeForRelated($query, 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sertifikat', 'like', '%' . $search . '%')
                    ->orWhereHas('mahasiswa', function ($subQ) use ($search) {
                        $subQ->where('nama_mahasiswa', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($angkatan) {
            $query->whereHas('mahasiswa', function ($q) use ($angkatan) {
                $q->where('id_angkatan', $angkatan);
            });
        }

        if ($jurusan) {
            $query->whereHas('mahasiswa', function ($q) use ($jurusan) {
                $q->where('id_jurusan', $jurusan);
            });
        }

        if ($keahlian) {
            $query->whereHas('mahasiswa', function ($q) use ($keahlian) {
                $q->where('id_keahlian', $keahlian);
            });
        }

        if ($status_pengajuan && $status_pengajuan !== 'Semua') {
            $query->where('status_pengajuan', $status_pengajuan);
        }

        $sertifikat = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        $statusOptions = Sertifikat::distinct()->pluck('status_pengajuan')->filter()->values();

        return view('dosen.sertifikat', compact(
            'sertifikat',
            'angkatans',
            'jurusans',
            'keahlians',
            'statusOptions',
            'search',
            'angkatan',
            'jurusan',
            'keahlian',
            'status_pengajuan',
            'selected_ids'
        ));
    }

    // DetailsSertifikat - ambil id dari query parameter
    public function DetailsSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Sertifikat ID is required');
        }

        $sertifikat = Sertifikat::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) {
                $this->getdosenFilterScope($query);
            })
            ->findOrFail($id);

        return view('dosen.sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }

    // approve - ambil id dari query parameter
    public function approve(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::whereHas('mahasiswa', function ($query) {
            $this->getdosenFilterScope($query);
        })
            ->findOrFail($id);

        $sertifikat->status_pengajuan = 'Di Terima';
        $sertifikat->is_active = true;
        $sertifikat->save();

        return redirect()->back()->with('success', 'Sertifikat berhasil diterima');
    }

    // reject - ambil id dari query parameter
    public function reject(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $request->validate(['keterangan' => 'required|string']);

        $sertifikat = Sertifikat::whereHas('mahasiswa', function ($query) {
            $this->getdosenFilterScope($query);
        })
            ->findOrFail($id);

        $sertifikat->status_pengajuan = 'Di Tolak';
        $sertifikat->is_active = false;
        $sertifikat->keterangan = $request->keterangan;
        $sertifikat->save();

        return redirect()->back()->with('success', 'Sertifikat ditolak');
    }

    // UpdateSertifikat - ambil id dari query parameter

    public function TambahSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa');

        $query = $this->getdosenFilterScope($query);

        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        $dosen = Auth::user();
        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }
        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        return view('dosen.sertifikat.views_create_sertifikat', compact(
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

    public function StoreSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'lembaga_penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'expired_date' => 'required_without:permanent|nullable|date|after:tanggal_terbit',
            'permanent' => 'nullable|boolean',
            'link_sertifikat' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
            'user_id' => 'required|string|max:255'
        ]);

        $user = User::where('id', $validated['user_id'])
            ->where('role', 'mahasiswa');
        $user = $this->getdosenFilterScope($user)->firstOrFail();

        if ($request->hasFile('link_sertifikat')) {
            $validated['link_sertifikat'] = ImageConversionService::storeWebp($request->file('link_sertifikat'), 'sertifikat');
        }

        Sertifikat::create([
            'id_mahasiswa' => $user->id,
            'nama_sertifikat' => $validated['nama_sertifikat'],
            'lembaga_penerbit' => $validated['lembaga_penerbit'],
            'tanggal_terbit' => $validated['tanggal_terbit'],
            'expired_date' => $request->has('permanent') ? null : $validated['expired_date'],
            'link_sertifikat' => $validated['link_sertifikat'],
            'status_pengajuan' => 'Di Terima',
            'is_active' => 1,
        ]);

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan!');
    }

    public function UpdateSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('dosen.sertifikat.index')
                ->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::whereHas('mahasiswa', function ($query) {
            $this->getdosenFilterScope($query);
        })
            ->findOrFail($id);

        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'lembaga_penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'expired_date' => 'required_without:permanent|nullable|date|after:tanggal_terbit',
            'permanent' => 'nullable|boolean',
            'link_sertifikat' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('link_sertifikat')) {
            if (
                $sertifikat->link_sertifikat &&
                Storage::disk('public')->exists($sertifikat->link_sertifikat)
            ) {
                Storage::disk('public')->delete($sertifikat->link_sertifikat);
            }
            $validated['link_sertifikat'] = ImageConversionService::storeWebp($request->file('link_sertifikat'), 'sertifikat');
        } else {
            $validated['link_sertifikat'] = $sertifikat->link_sertifikat;
        }

        $validated['expired_date'] = $request->has('permanent') ? null : $validated['expired_date'];

        $sertifikat->update($validated);

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    // DestroySertifikat - ambil id dari query parameter
    public function DestroySertifikat(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('dosen.sertifikat.index')
                ->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::whereHas('mahasiswa', function ($query) {
            $this->getdosenFilterScope($query);
        })
            ->findOrFail($id);

        if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
            Storage::disk('public')->delete($sertifikat->link_sertifikat);
        }

        $sertifikat->delete();

        return redirect()->route('dosen.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    // bulkDestroy - perbaiki untuk menerima berbagai format
    public function bulkDestroy(Request $request)
    {
        $this->authorizeAccess();

        // Ambil dari berbagai kemungkinan format
        $ids = $request->input('selected_ids', []);

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sertifikat terpilih untuk dihapus.'
            ], 422);
        }

        try {
            $sertifikats = Sertifikat::whereIn('id', $ids)
                ->whereHas('mahasiswa', function ($query) {
                    $this->getdosenFilterScope($query);
                })
                ->get();

            foreach ($sertifikats as $sertifikat) {
                if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
                    Storage::disk('public')->delete($sertifikat->link_sertifikat);
                }
            }

            Sertifikat::whereIn('id', $sertifikats->pluck('id'))->delete();

            return response()->json([
                'success' => true,
                'message' => count($sertifikats) . ' sertifikat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    //For Projects Pages
    public function projects(Request $request)
    {
        $this->authorizeAccess();

        $search = trim($request->input('search'));

        $query = Project::with(['mahasiswa', 'leader', 'members'])
            ->where(function ($query) {
                $query->whereHas('mahasiswa', function ($q) {
                    $this->getdosenFilterScope($q);
                })
                    ->orWhereHas('leader', function ($q) {
                        $this->getdosenFilterScope($q);
                    })
                    ->orWhereHas('members', function ($q) {
                        $this->getdosenFilterScope($q);
                    });
            });

        // Search hanya nama project
        if ($search) {
            $query->where('isi_content->nama_project', 'like', '%' . $search . '%');
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        return view('dosen.project', compact('projects', 'search'));
    }

    public function TambahProjects(Request $request)
    {
        $this->authorizeAccess();

        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query mahasiswa + relasi
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima');

        // Scope filter dosen
        $query = $this->getdosenFilterScope($query);

        // Search nama / username / email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter angkatan
        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        // Filter jurusan
        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        // Filter keahlian
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        // Pagination
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Semua user untuk JS multi member
        $allUsers = $query->orderBy('created_at', 'desc')->get();

        // Dropdown data
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        // Batasi dropdown sesuai data dosen
        $dosen = Auth::user();

        if ($dosen->id_jurusan) {
            $jurusans = $jurusans->where('id_jurusan', $dosen->id_jurusan);
        }

        if ($dosen->id_angkatan) {
            $angkatans = $angkatans->where('id', $dosen->id_angkatan);
        }

        // AJAX request
        if ($request->ajax()) {

            $userListHtml = '';

            if ($users->count() > 0) {
                foreach ($users as $user) {

                    $photo = $user->photo_profile
                        ? '<img src="/storage/' . $user->photo_profile . '" class="w-10 h-10 rounded-full object-cover">'
                        : '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">'
                        . strtoupper(substr($user->nama_mahasiswa, 0, 1)) .
                        '</span>
                       </div>';

                    $userListHtml .= '
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        ' . $photo . '
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">'
                        . e($user->nama_mahasiswa) .
                        '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none" title="'
                        . e($user->email) . '">'
                        . e($user->email) .
                        '</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm"
                            onchange="updateUserRole(this, ' . $user->id . ', this.value)">
                            <option value="">-- Pilih Role --</option>
                            <option value="owner">Owner</option>
                            <option value="leader">Leader</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                </div>';
                }
            } else {
                $userListHtml = '
            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                Tidak ada mahasiswa yang sesuai filter.
            </div>';
            }

            $paginationHtml = $users->render(
                'vendor.pagination.custom_ajax',
                ['groupName' => 'dosen_project_user_selection']
            )->toHtml();

            return response()->json([
                'userListHtml' => $userListHtml,
                'paginationHtml' => $paginationHtml,
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
            ]);
        }

        return view('dosen.projects.views_create_project', compact(
            'users',
            'allUsers',
            'angkatans',
            'jurusans',
            'keahlians',
            'search',
            'angkatan',
            'jurusan',
            'keahlian'
        ));
    }

    // Edit Projects Dosen
public function EditProjects(Request $request)
{
    $this->authorizeAccess();
    $id = $request->query('id');

    if (!$id) {
        abort(404, 'Project ID is required');
    }

    // Get search & filter inputs
    $search    = $request->input('search');
    $angkatan  = $request->input('angkatan');
    $jurusan   = $request->input('jurusan');
    $keahlian  = $request->input('keahlian');

    // Base query mahasiswa
    $baseQuery = User::with(['jurusan', 'angkatan', 'keahlian'])
        ->where('role', 'mahasiswa')
        ->where('status_pengajuan', 'Di Terima');

    // Search nama / username / email
    if ($search) {
        $baseQuery->where(function ($q) use ($search) {
            $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    // Filter dropdown
    if ($angkatan) {
        $baseQuery->where('id_angkatan', $angkatan);
    }

    if ($jurusan) {
        $baseQuery->where('id_jurusan', $jurusan);
    }

    if ($keahlian) {
        $baseQuery->where('id_keahlian', $keahlian);
    }

    // Semua user untuk JS multi select semua halaman
    $allUsers = (clone $baseQuery)->orderBy('created_at', 'desc')->get();

    // Dropdown filter data
    $angkatans = Angkatan::all();
    $jurusans  = Jurusan::all();
    $keahlians = Keahlian::all();

    // Load project
    $project = Project::with(['members', 'tasks', 'leader'])
        ->where('id', $id)
        ->firstOrFail();

    // User terpilih
    $selectedUserIds = collect([$project->id_mahasiswa, $project->leader_id])
        ->merge($project->members->pluck('id'))
        ->filter()
        ->unique()
        ->values()
        ->all();

    $selectedUsers = User::whereIn('id', $selectedUserIds)->get();

    // AJAX Request
    if ($request->ajax()) {
        // Clone query untuk pagination
        $paginatedQuery = clone $baseQuery;
        $users = $paginatedQuery->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $userListHtml = '';

        if ($users->count() > 0) {
            foreach ($users as $user) {

                $userListHtml .= '
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
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
                            <div class="font-medium text-gray-900 dark:text-gray-100">' . $user->nama_mahasiswa . '</div>

                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none"
                                title="' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '">
                                ' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm"
                            onchange="updateUserRole(this, ' . $user->id . ', this.value)">
                            
                            <option value="">-- Pilih Role --</option>
                            <option value="owner">Owner</option>
                            <option value="leader">Leader</option>
                            <option value="member">Member</option>

                        </select>
                    </div>
                </div>';
            }
        } else {
            $userListHtml = '
            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                Tidak ada mahasiswa yang sesuai filter.
            </div>';
        }

        $paginationHtml = $users
            ->render('vendor.pagination.custom_ajax', [
                'groupName' => 'dosen_project_user_selection'
            ])->toHtml();

        return response()->json([
            'userListHtml'   => $userListHtml,
            'paginationHtml' => $paginationHtml,
            'currentPage'    => $users->currentPage(),
            'lastPage'       => $users->lastPage(),
        ]);
    }

    // Untuk non-AJAX request (first load)
    $usersQuery = clone $baseQuery;
    $users = $usersQuery->orderBy('created_at', 'desc')->get();

    return view('dosen.projects.views_edit_project', compact(
        'project',
        'users',
        'allUsers',
        'selectedUsers',
        'angkatans',
        'jurusans',
        'keahlians',
        'search',
        'angkatan',
        'jurusan',
        'keahlian'
    ));
}

    /**
     * Cek apakah nama_project sudah dipakai project lain (case-insensitive, trimmed).
     * $excludeId dipakai saat update supaya project itu sendiri tidak dianggap duplikat.
     */
    protected function isDuplicateProjectName(string $name, ?int $excludeId = null): bool
    {
        $normalized = strtolower(trim($name));
        if ($normalized === '') {
            return false;
        }

        return Project::query()
            ->whereRaw(
                "LOWER(TRIM(JSON_UNQUOTE(JSON_EXTRACT(isi_content, '$.nama_project')))) = ?",
                [$normalized]
            )
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    // Endpoint AJAX untuk cek duplikat nama project secara real-time dari form dosen
    public function checkDuplicateName(Request $request)
    {
        $this->authorizeAccess();

        $name = trim((string) $request->input('nama_project', ''));
        $excludeId = $request->input('exclude_id');

        if ($name === '') {
            return response()->json(['is_duplicate' => false, 'message' => '']);
        }

        $isDuplicate = $this->isDuplicateProjectName($name, $excludeId ? (int) $excludeId : null);

        return response()->json([
            'is_duplicate' => $isDuplicate,
            'message' => $isDuplicate
                ? "Nama project \"{$name}\" sudah digunakan. Silakan gunakan nama lain."
                : '',
        ]);
    }

    public function StoreProject(Request $request)
    {
        $this->authorizeAccess();

        if (empty($request->owner) && !empty($request->leader)) {
            $request->merge(['owner' => $request->leader]);
        }

        $request->merge([
            'members' => collect($request->members)
                ->filter(fn($id) => !empty($id))
                ->values()
                ->all(),
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) || !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:500',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'leader' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id|different:leader',
            'tasks' => 'nullable|array',
            'tasks.*.user_id' => 'required_with:tasks|exists:users,id',
            'tasks.*.name_task' => 'required_with:tasks|string|max:255'
        ]);

        // Ensure leader is within dosen's scope
        if ($request->leader) {
            $leader = User::where('id', $request->leader)
                ->where('role', 'mahasiswa');
            $leader = $this->getdosenFilterScope($leader)->firstOrFail();
        }

        // Ensure all members are within dosen's scope
        if ($request->members) {
            $memberIds = collect($request->members)->filter()->values()->toArray();
            if (!empty($memberIds)) {
                $members = User::whereIn('id', $memberIds)
                    ->where('role', 'mahasiswa');
                $members = $this->getdosenFilterScope($members)->get();

                if ($members->count() != count($memberIds)) {
                    return back()->withInput()->withErrors(['members' => 'Beberapa anggota tidak valid atau tidak dalam cakupan Anda.']);
                }
            }
        }

        if ($this->isDuplicateProjectName($request->nama_project)) {
            return back()
                ->withInput()
                ->withErrors(['nama_project' => "Nama project \"{$request->nama_project}\" sudah digunakan. Silakan gunakan nama lain."]);
        }

        // Siapkan content sebagai JSON
        $content = [
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'link_project' => $request->link_project,
            'link_github' => $request->link_github,
            'link_video' => $request->link_video
        ];

        // Filter out null values
        $content = array_filter($content, fn($value) => !is_null($value) && $value !== '');

        // Validasi minimal ada content yang diisi
        if (empty($content)) {
            return back()->withInput()->withErrors(['project' => 'Minimal isi salah satu field (deskripsi, link_project, link_github, atau link_video)']);
        }

        // Buat project baru
        $project = Project::create([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => $request->owner,
            'leader_id' => $request->leader,
        ]);

        // Proses members (rekan project)
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($id) => $id == $request->leader || $id == $request->owner)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        // Attach members ke project (jika ada)
        if (!empty($members)) {
            $project->members()->attach($members);
        }

        // Proses tugas per orang jika dikirim
        $tasks = collect($request->input('tasks', []))->filter(function ($task) {
            return is_array($task)
                && !empty($task['user_id'])
                && !empty(trim($task['name_task'] ?? ''));
        })->values()->all();

        if (!empty($tasks)) {
            $this->createProjectTasks($project, $tasks);
        }

        return redirect()->route('dosen.projects.index')
            ->with('success', 'Project berhasil ditambahkan!')
            ->with('clear_local_storage', true);
    }

    public function UpdateProject(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('dosen.projects.index')
                ->with('error', 'Project ID tidak ditemukan');
        }

        $project = Project::findOrFail($id);

        if (empty($request->owner) && !empty($request->leader)) {
            $request->merge(['owner' => $request->leader]);
        }

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'owner' => 'required|exists:users,id',
            'leader' => 'nullable|exists:users,id',
            'is_collaborative' => 'boolean',
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id',
            'tasks' => 'nullable|array',
            'tasks.*.id' => 'sometimes|nullable|integer|exists:project_tasks,id',
            'tasks.*.user_id' => 'required_with:tasks|exists:users,id',
            'tasks.*.name_task' => 'required_with:tasks|string|max:255'
        ]);

        if ($this->isDuplicateProjectName($request->nama_project, (int) $project->id)) {
            return back()
                ->withInput()
                ->withErrors(['nama_project' => "Nama project \"{$request->nama_project}\" sudah digunakan. Silakan gunakan nama lain."]);
        }

        // Prepare content array
        $content = [
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'link_project' => $request->link_project,
            'link_github' => $request->link_github,
            'link_video' => $request->link_video
        ];

        // Hapus yang kosong/null
        $content = array_filter($content, fn($value) => !is_null($value) && $value !== '');

        // Minimal 1 field harus ada
        if (empty($content)) {
            return back()->withInput()
                ->withErrors(['project' => 'Minimal isi salah satu field (nama_project, deskripsi, atau link)']);
        }

        $isCollaborative = $request->boolean('is_collaborative', true);

        // Logika khusus: jika owner diubah dan ada leader yang berbeda jurusan/angkatan/keahlian dari dosen, maka leader naik tingkat
        $dosen = Auth::user();
        $newOwnerId = $request->owner;
        $newLeaderId = $isCollaborative ? $request->leader : null;

        if ($project->id_mahasiswa != $newOwnerId && $newLeaderId) {
            // Cek apakah leader berbeda dari dosen dalam jurusan/angkatan/keahlian
            $leader = User::find($newLeaderId);
            $isDifferent = ($leader->id_jurusan != $dosen->id_jurusan) ||
                ($leader->id_angkatan != $dosen->id_angkatan) ||
                ($leader->id_keahlian != $dosen->id_keahlian);

            if ($isDifferent) {
                // Leader naik tingkat menjadi owner
                $newOwnerId = $newLeaderId;
                $newLeaderId = null; // Leader kosong karena sudah naik

                // Hapus tugas owner lama yang dimiliki dosen
                ProjectTask::where('project_id', $project->id)
                    ->where('user_id', $dosen->id)
                    ->delete();
            }
        }

        // Update project
        $project->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => $newOwnerId,
            'leader_id' => $newLeaderId,
            'is_collaborative' => $isCollaborative,
        ]);

        // Handle members berdasarkan mode kolaboratif
        if ($isCollaborative) {
            $members = collect($request->members ?? [])
                ->filter()
                ->reject(fn($memberId) => $memberId == $newOwnerId || $memberId == $newLeaderId)
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            $project->members()->sync($members);
        } else {
            // Mode non-kolaboratif: hapus semua members
            $project->members()->sync([]);
        }

        // Handle tasks
        $this->handleTasks($project, $request->input('tasks', []), $isCollaborative, $newOwnerId, $newLeaderId);

        return redirect()->route('dosen.projects.index')
            ->with('success', 'Project berhasil diperbarui!');
    }

    private function handleTasks($project, array $tasks, bool $isCollaborative, $ownerId, $leaderId = null)
    {
        $project = $project->fresh(['members', 'tasks']);

        // Kumpulkan semua user yang diizinkan
        $allowedUserIds = collect([(int) $ownerId]);

        if ($isCollaborative) {
            if ($leaderId) {
                $allowedUserIds->push((int) $leaderId);
            }
            $allowedUserIds = $allowedUserIds->merge(
                $project->members->pluck('id')->map(fn($id) => (int) $id)
            );
        }

        $allowedUserIds = $allowedUserIds->filter()->unique()->values()->all();

        $keptTaskIds = [];

        foreach ($tasks as $task) {
            $taskId = isset($task['id']) && is_numeric($task['id']) ? (int) $task['id'] : null;
            $userId = isset($task['user_id']) && is_numeric($task['user_id']) ? (int) $task['user_id'] : null;
            $nameTask = isset($task['name_task']) ? trim($task['name_task']) : '';

            // Lewati jika tidak ada user_id atau nama tugas kosong
            if (!$userId || empty($nameTask)) {
                continue;
            }

            // Cek apakah user masih diizinkan
            if (!in_array($userId, $allowedUserIds, true)) {
                continue;
            }

            if ($taskId) {
                // Update existing task
                $existing = ProjectTask::where('project_id', $project->id)
                    ->where('id', $taskId)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'user_id' => $userId,
                        'name_task' => $nameTask,
                    ]);
                    $keptTaskIds[] = $existing->id;
                    continue;
                }
            }

            // Create new task
            $newTask = ProjectTask::create([
                'project_id' => $project->id,
                'user_id' => $userId,
                'name_task' => $nameTask,
                'is_done' => false,
            ]);

            $keptTaskIds[] = $newTask->id;
        }

        // Hapus task yang tidak ada di daftar yang dikirim (kecuali yang sudah dihapus di frontend)
        if (!empty($keptTaskIds)) {
            ProjectTask::where('project_id', $project->id)
                ->whereNotIn('id', $keptTaskIds)
                ->delete();
        } else {
            // Tidak ada task yang valid → hapus semua
            ProjectTask::where('project_id', $project->id)->delete();
        }

        // Cleanup akhir: hapus task milik user yang sudah tidak boleh (misal leader diubah, member dihapus)
        ProjectTask::where('project_id', $project->id)
            ->whereNotIn('user_id', $allowedUserIds)
            ->delete();
    }

    // DestroyProject - ambil id dari query parameter
    public function DestroyProject(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Project ID tidak ditemukan');
        }

        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Project tidak ditemukan');
        }

        $project->delete();

        return redirect()->back()->with('success', 'Project berhasil dihapus');
    }

    public function bulkDestroyProject(Request $request)
    {
        $this->authorizeAccess();

        $selectedIds = $request->input('selected_ids', []);

        if (is_string($selectedIds)) {
            $decoded = json_decode($selectedIds, true);
            if (is_array($decoded)) {
                $selectedIds = $decoded;
            } else {
                $selectedIds = explode(',', $selectedIds);
            }
        }

        if (!is_array($selectedIds)) {
            $selectedIds = [$selectedIds];
        }

        $ids = array_values(array_filter(array_map('intval', $selectedIds)));

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada project terpilih untuk dihapus.');
        }

        Project::whereIn('id', $ids)
            ->whereHas('mahasiswa', function ($query) {
                $this->getdosenFilterScope($query);
            })
            ->delete();

        return redirect()->back()->with('success', count($ids) . ' Project berhasil dihapus');
    }

    public function manageUsers()
    {
        $this->authorizeAccess();

        $users = User::query();

        // Retrieve all Angkatan data
        $angkatans = Angkatan::all();

        // Pass $angkatans to the view
        return view('dosen.daftar-mahasiswa', compact('users', 'angkatans'));
    }
}