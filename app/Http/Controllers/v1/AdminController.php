<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Sertifikat;
use App\Models\Keahlian;
use App\Models\Keahlian_Tambahan;
use App\Models\Jurusan;
use App\Models\Angkatan;
use App\Models\LearningCorner;
use App\Models\Notification;
use App\Http\Controllers\v1\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'admin') {
            abort(Response::HTTP_FORBIDDEN, 'Akses hanya untuk Administrator.');
        }
    }

    private function createProjectTasks(Project $project, array $tasks)
    {
        $project = $project->fresh(['members']);
        $allowedUserIds = collect();

        // Owner
        if (!empty($project->id_mahasiswa)) {
            $allowedUserIds->push((int) $project->id_mahasiswa);
        }

        // Leader
        if (!empty($project->leader_id)) {
            $allowedUserIds->push((int) $project->leader_id);
        }

        // Members
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

            if (!in_array($taskUserId, $allowedUserIds, true)) {
                Log::warning('Task user not allowed for project', [
                    'project_id' => $project->id,
                    'task_user_id' => $taskUserId,
                    'allowed_users' => $allowedUserIds
                ]);
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

    public function index()
    {
        $this->authorizeAccess();

        $totalUsers = User::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalDosen = User::where('role', 'dosen')->count();

        $totalProjects = Project::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();
        $totalLearningCorners = LearningCorner::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();
        $totalSertifikats = Sertifikat::whereHas('mahasiswa', fn($query) => $query->where('role', 'mahasiswa'))->count();

        $latestActivities = Project::with('mahasiswa')
            ->latest('created_at')
            ->take(3)
            ->get();

        $pendingMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->latest('created_at')
            ->take(3)
            ->get();

        $rejectedMahasiswa = User::where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Tolak')
            ->latest('created_at')
            ->take(3)
            ->get();

        return view('admin.index', compact(
            'totalUsers',
            'totalMahasiswa',
            'totalAdmin',
            'totalDosen',
            'totalProjects',
            'totalLearningCorners',
            'totalSertifikats',
            'latestActivities',
            'pendingMahasiswa',
            'rejectedMahasiswa'

        ));
    }

    //For Pages User
    public function ListUser(Request $request)
{
    $this->authorizeAccess();

    $query = User::whereIn('role', ['mahasiswa', 'admin', 'dosen'])
        ->where('id', '!=', Auth::id())
        ->with(['jurusan', 'angkatan', 'keahlian'])
        ->withCount([
            'projects',
            'sertifikats',
            'learning_corners'
        ]);

    // Filter search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_mahasiswa', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filter role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Filter jurusan
    if ($request->filled('jurusan')) {
        $query->where('id_jurusan', $request->jurusan);
    }

    // Filter status pengajuan
    if ($request->filled('status_pengajuan')) {
        $query->where('status_pengajuan', $request->status_pengajuan);
    }

    $users = $query
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString(); // agar filter tetap saat pindah halaman

    $pendingKeahlianTambahanCount = \App\Models\Keahlian_Tambahan::where(
        'status_pengajuan',
        'Sedang Di Ajukan'
    )->count();

    $jurusan = Jurusan::all();

    return view(
        'admin.daftar-mahasiswa',
        compact('users', 'pendingKeahlianTambahanCount', 'jurusan')
    );
}

    public function ViewAddUser()
    {
        $this->authorizeAccess();
        $jurusan = Jurusan::all();
        $keahlian = Keahlian::all();
        $angkatan = Angkatan::all();
        return view('admin.user.views_add_user', compact('jurusan', 'keahlian', 'angkatan'));
    }

    public function AddUser(Request $request)
    {
        $this->authorizeAccess();

        // Rules dasar
        $rules = [
            'role' => ['required', 'in:mahasiswa,dosen,admin'],
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'username' => [
                'nullable',
                'string',
                'max:100',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->mixedCase()
            ],
            'photo_profile' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
            'avatar_default' => ['nullable', 'string', 'in:WanitaAVA,LakiAVA'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
        ];

        // Validasi dinamis berdasarkan role DAN registration_type
        if ($request->role === 'admin') {
            // Admin: username dan password WAJIB, tanggal_lahir dan nim TIDAK PERLU
            $rules['username'] = ['required', 'string', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'];
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()];
            // Hapus rules tanggal_lahir dari array (biarkan nullable)
            unset($rules['tanggal_lahir']);

        } elseif ($request->role === 'dosen') {
            // Dosen: username, password, tanggal_lahir WAJIB, nim TIDAK PERLU
            $rules['username'] = ['required', 'string', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'];
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()];
            $rules['tanggal_lahir'] = ['required', 'date', 'before_or_equal:today'];
            $rules['id_jurusan'] = ['required', 'exists:jurusan,id_jurusan'];
            $rules['id_keahlian'] = ['required', 'exists:keahlian,id_keahlian'];
            $rules['id_angkatan'] = ['required', 'exists:angkatan,id'];

        } elseif ($request->role === 'mahasiswa') {
            $registrationType = $request->registration_type;

            if ($registrationType === 'simple') {
                $rules['nim'] = ['required', 'string', 'unique:users,nim', 'max:20'];
                $rules['tanggal_lahir'] = ['nullable', 'date', 'before_or_equal:today'];
                $rules['id_jurusan'] = ['required', 'exists:jurusan,id_jurusan'];
                $rules['id_keahlian'] = ['required', 'exists:keahlian,id_keahlian'];
                $rules['id_angkatan'] = ['required', 'exists:angkatan,id'];

            } else { // full registration
                $rules['nim'] = ['required', 'string', 'unique:users,nim', 'max:20'];
                $rules['username'] = ['required', 'string', 'max:100', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'];
                $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()];
                $rules['tanggal_lahir'] = ['required', 'date', 'before_or_equal:today'];
                $rules['id_jurusan'] = ['required', 'exists:jurusan,id_jurusan'];
                $rules['id_keahlian'] = ['required', 'exists:keahlian,id_keahlian'];
                $rules['id_angkatan'] = ['required', 'exists:angkatan,id'];
            }
        }

        $validated = $request->validate($rules);

        // Penanganan foto profil
        $photoPath = null;

        if ($request->hasFile('photo_profile')) {
            $photoPath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
        } elseif ($request->filled('avatar_default')) {
            $avatarName = $request->avatar_default;
            $sourcePath = public_path("assets/{$avatarName}.png");

            if (file_exists($sourcePath)) {
                $tempFile = new UploadedFile(
                    $sourcePath,
                    $avatarName . '.png',
                    'image/png',
                    null,
                    true
                );
                $photoPath = ImageConversionService::storeWebp($tempFile, 'photos');
            } else {
                \Log::warning("Avatar file not found: {$sourcePath}");
            }
        }

        // === PERBAIKAN UTAMA: Siapkan data user ===
        $userData = [
            'nama_mahasiswa' => $validated['nama_mahasiswa'],
            'email' => $validated['email'] ?? null,
            'username' => $validated['username'] ?? null,
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : null,
            'photo_profile' => $photoPath,
            'role' => $validated['role'],
            'id_jurusan' => $validated['id_jurusan'] ?? null,
            'id_keahlian' => $validated['id_keahlian'] ?? null,
            'id_angkatan' => $validated['id_angkatan'] ?? null,
            'status_pengajuan' => 'Di Terima',
            'is_active' => 1,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null, // Admin akan NULL, Dosen akan terisi
        ];

        // Tambahkan NIM hanya jika ada (hanya untuk mahasiswa)
        if (isset($validated['nim'])) {
            $userData['nim'] = $validated['nim'];
        } else {
            $userData['nim'] = null; // Explicit set null untuk admin dan dosen
        }

        User::create($userData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function importExcel(Request $request)
    {
        $this->authorizeAccess();

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

            // Ambil referensi dari DB (key = nama lowercase => id)
            $jurusanMap = Jurusan::pluck('id_jurusan', 'nama_jurusan')
                ->mapWithKeys(fn($id, $name) => [strtolower(trim($name)) => $id])
                ->toArray();
            $keahlianMap = Keahlian::pluck('id_keahlian', 'nama_keahlian')
                ->mapWithKeys(fn($id, $name) => [strtolower(trim($name)) => $id])
                ->toArray();
            $angkatanMap = Angkatan::pluck('id', 'nama_angkatan')
                ->mapWithKeys(fn($id, $name) => [strtolower(trim($name)) => $id])
                ->toArray();

            $successCount = 0;
            $failedCount = 0;
            $failedRows = [];
            $warnings = [];

            $notFoundJurusan = [];
            $notFoundKeahlian = [];
            $notFoundAngkatan = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // baris 1 = header

                try {
                    // --- Mapping kolom ---
                    $nim = trim($row['nim'] ?? $row['NIM'] ?? '');
                    $nama = trim($row['nama'] ?? $row['Nama'] ?? $row['Nama Lengkap'] ?? '');
                    $tanggalLahir = $this->parseDate($row['tanggal_lahir'] ?? $row['Tanggal Lahir'] ?? '');
                    $namaJurusan = strtolower(trim($row['jurusan'] ?? $row['Jurusan'] ?? ''));
                    $namaKeahlian = strtolower(trim($row['keahlian'] ?? $row['Keahlian'] ?? ''));
                    $namaAngkatan = strtolower(trim($row['angkatan'] ?? $row['Angkatan'] ?? ''));

                    // --- Validasi wajib ---
                    if (empty($nim) || empty($nama)) {
                        $failedCount++;
                        $failedRows[] = ['row' => $rowNumber, 'reason' => 'NIM atau Nama tidak boleh kosong'];
                        continue;
                    }

                    // --- Cek duplikat NIM ---
                    if (User::where('nim', $nim)->exists()) {
                        $failedCount++;
                        $failedRows[] = ['row' => $rowNumber, 'reason' => "NIM {$nim} sudah terdaftar"];
                        continue;
                    }

                    // --- Resolve FK (nullable jika tidak ditemukan) ---
                    $idJurusan = null;
                    if (!empty($namaJurusan)) {
                        if (isset($jurusanMap[$namaJurusan])) {
                            $idJurusan = $jurusanMap[$namaJurusan];
                        } else {
                            $notFoundJurusan[] = $row['jurusan'] ?? $row['Jurusan'] ?? $namaJurusan;
                        }
                    }

                    $idKeahlian = null;
                    if (!empty($namaKeahlian)) {
                        if (isset($keahlianMap[$namaKeahlian])) {
                            $idKeahlian = $keahlianMap[$namaKeahlian];
                        } else {
                            $notFoundKeahlian[] = $row['keahlian'] ?? $row['Keahlian'] ?? $namaKeahlian;
                        }
                    }

                    $idAngkatan = null;
                    if (!empty($namaAngkatan)) {
                        if (isset($angkatanMap[$namaAngkatan])) {
                            $idAngkatan = $angkatanMap[$namaAngkatan];
                        } else {
                            $notFoundAngkatan[] = $row['angkatan'] ?? $row['Angkatan'] ?? $namaAngkatan;
                        }
                    }

                    // --- Buat user ---
                    // Password default = NIM (di-hash)
                    // username = null, tidak di-generate
                    User::create([
                        'nim' => $nim,
                        'nama_mahasiswa' => $nama,
                        'username' => null,
                        'email' => null,
                        'password' => Hash::make($nim),
                        'tanggal_lahir' => $tanggalLahir,
                        'id_jurusan' => $idJurusan,
                        'id_keahlian' => $idKeahlian,
                        'id_angkatan' => $idAngkatan,
                        'role' => 'mahasiswa',
                        'status_pengajuan' => 'Di Terima',
                        'is_active' => 1,
                        'photo_profile' => null,
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $failedCount++;
                    $failedRows[] = ['row' => $rowNumber, 'reason' => 'Error: ' . $e->getMessage()];
                    \Log::error("Import baris {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            // --- Kumpulkan warning referensi tidak ditemukan ---
            if (!empty($notFoundJurusan)) {
                $warnings[] = 'Jurusan tidak ditemukan: ' . implode(', ', array_unique($notFoundJurusan));
            }
            if (!empty($notFoundKeahlian)) {
                $warnings[] = 'Keahlian tidak ditemukan: ' . implode(', ', array_unique($notFoundKeahlian));
            }
            if (!empty($notFoundAngkatan)) {
                $warnings[] = 'Angkatan tidak ditemukan: ' . implode(', ', array_unique($notFoundAngkatan));
            }

            return response()->json([
                'success' => true,
                'message' => "Import selesai: {$successCount} berhasil, {$failedCount} gagal.",
                'warnings' => $warnings,
                'failedRows' => $failedRows,
                'stats' => [
                    'success' => $successCount,
                    'failed' => $failedCount,
                    'total' => count($data),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Import Excel error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // Helper: parse file Excel → array of associative arrays
    // ============================================================
    private function parseExcelFile($file): array
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

            if (count($rows) < 2) {
                return [];
            }

            // Baris pertama = header, normalize ke lowercase
            $rawHeaders = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);

            // Map header ke key standar yang dikenali importExcel()
            $headerAliases = [
                'nim' => ['nim', 'n i m'],
                'Nama Lengkap' => ['nama lengkap', 'nama', 'full name'],
                'Tanggal Lahir' => ['tanggal lahir', 'tgl lahir', 'birth date', 'birthdate'],
                'Jurusan' => ['jurusan', 'department', 'prodi'],
                'Keahlian' => ['keahlian', 'bidang keahlian', 'skill'],
                'Angkatan' => ['angkatan', 'tahun masuk', 'year'],
            ];

            // Buat peta: index kolom => key standar
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

                // Lewati baris kosong
                $allEmpty = true;
                foreach ($row as $cell) {
                    if (!empty(trim((string) $cell))) {
                        $allEmpty = false;
                        break;
                    }
                }
                if ($allEmpty) {
                    continue;
                }

                $mapped = [];
                foreach ($colMap as $colIndex => $standardKey) {
                    $mapped[$standardKey] = isset($row[$colIndex]) ? trim((string) $row[$colIndex]) : '';
                }

                // Hanya masukkan baris yang punya NIM
                if (!empty($mapped['nim'] ?? $mapped['NIM'] ?? '')) {
                    $data[] = $mapped;
                }
            }

            return $data;

        } catch (\Exception $e) {
            \Log::error('parseExcelFile error: ' . $e->getMessage());
            return [];
        }
    }

    // ============================================================
    // Helper: parse tanggal dari berbagai format
    // ============================================================
    private function parseDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        // Serial date Excel (angka)
        if (is_numeric($date)) {
            $unix = ((int) $date - 25569) * 86400;
            return date('Y-m-d', $unix);
        }

        $date = trim((string) $date);

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
        $user = User::with(['jurusan', 'keahlian', 'angkatan'])->findOrFail($id);

        return view('admin.user.views_edit_user', compact(
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
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'User ID is required');
        }

        $user = User::findOrFail($id);

        // Base validation rules
        $rules = [
            'nama_mahasiswa' => ['sometimes', 'string', 'max:100'],
            'username' => [
                'sometimes',
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
            'role' => [
                'sometimes',
                'in:mahasiswa,dosen,admin'
            ],
            'status_pengajuan' => [
                'sometimes',
                'in:Di Terima,Di Tolak'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
            ],
            'nim' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id)
            ],
            'tanggal_lahir' => [
                'sometimes',
                'nullable',
                'date'
            ],
            'id_jurusan' => [
                'sometimes',
                'nullable',
                'exists:jurusan,id_jurusan'
            ],
            'id_keahlian' => [
                'sometimes',
                'nullable',
                'exists:keahlian,id_keahlian'
            ],
            'id_angkatan' => [
                'sometimes',
                'nullable',
                'exists:angkatan,id'
            ],
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(8)->mixedCase(),
                'regex:/^\S*$/'
            ],
            'photo_profile' => [
                'sometimes',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'background_url' => [
                'sometimes',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096'
            ],
        ];

        $validated = $request->validate($rules);

        $updateData = [];

        // Update basic fields
        foreach ([
            'nama_mahasiswa',
            'username',
            'email',
            'role',
            'status_pengajuan',
            'is_active',
            'id_jurusan',
            'id_keahlian',
            'id_angkatan',
            'nim',
            'tanggal_lahir',
        ] as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $validated[$field] ?? null;
            }
        }

        // Password update
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Role logic
        if ($request->has('role') && $validated['role'] === 'admin') {
            $updateData['nim'] = null;
            $updateData['tanggal_lahir'] = null;
            $updateData['id_jurusan'] = null;
            $updateData['id_keahlian'] = null;
            $updateData['id_angkatan'] = null;
        }

        if ($request->has('role') && $validated['role'] === 'dosen') {
            $updateData['nim'] = null;
        }

        // Photo upload
        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $photoPath = ImageConversionService::storeWebp($request->file('photo_profile'), 'photos');
            $updateData['photo_profile'] = $photoPath;
        }

        // Background upload
        if ($request->hasFile('background_url')) {
            if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
                Storage::disk('public')->delete($user->background_url);
            }
            $backgroundPath = ImageConversionService::storeWebp($request->file('background_url'), 'covers');
            $updateData['background_url'] = $backgroundPath;
        }

        // Update user
        $user->update($updateData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // updateStatus - ambil id dari query parameter
    public function updateStatus(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'User ID is required');
        }

        $user = User::findOrFail($id);

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
            ->route('admin.users.index')
            ->with('success', "Status pengajuan {$nama} berhasil diupdate menjadi {$user->status_pengajuan}");
    }

    // destroyUser - ambil id dari query parameter
    public function destroyUser(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User ID tidak ditemukan');
        }

        $user = User::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
            Storage::disk('public')->delete($user->background_url);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // bulkDestroyUsers - tetap sama karena sudah menggunakan request body
    public function bulkDestroyUsers(Request $request)
    {
        $this->authorizeAccess();
        $ids = $request->input('selected_ids');

        // Handle jika dikirim sebagai JSON string (dari JS)
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()
                ->with('error', 'Tidak ada pengguna yang dipilih untuk dihapus.');
        }

        // Optional: Tambahkan pengecekan agar admin tidak bisa menghapus dirinya sendiri
        $currentUserId = Auth::id();
        $ids = array_filter($ids, function ($id) use ($currentUserId) {
            return $id != $currentUserId;
        });

        // Hapus gambar dari storage untuk user yang akan dihapus
        $users = User::whereIn('id', $ids)->get();
        foreach ($users as $user) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            if ($user->background_url && Storage::disk('public')->exists($user->background_url)) {
                Storage::disk('public')->delete($user->background_url);
            }
        }

        $count = User::whereIn('id', $ids)->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "{$count} pengguna berhasil dihapus secara permanen.");
    }

    // Bulk approve users: set status_pengajuan => 'Di Terima'
    public function bulkApproveUsers(Request $request)
    {
        $this->authorizeAccess();

        $ids = $request->input('selected_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih untuk disetujui.');
        }

        // Only approve those that are currently awaiting approval
        $affected = User::whereIn('id', $ids)
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->update(['status_pengajuan' => 'Di Terima']);

        return redirect()->route('admin.users.index')
            ->with('success', "{$affected} pengguna berhasil disetujui.");
    }

    // For Pages User Keahlian Tambahan
    public function ListUserKeahlianTambahan(Request $request)
    {
        $this->authorizeAccess();

        $applications = Keahlian_Tambahan::with(['mahasiswa.jurusan', 'mahasiswa.angkatan', 'keahlian'])
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->has('search') && $request->search !== '') {
            $search = strtolower($request->input('search'));

            $applications = $applications->filter(function ($application) use ($search) {
                return str_contains(strtolower($application->mahasiswa->nama_mahasiswa ?? ''), $search) ||
                    str_contains(strtolower($application->mahasiswa->username ?? ''), $search) ||
                    str_contains(strtolower($application->mahasiswa->email ?? ''), $search) ||
                    str_contains(strtolower($application->keahlian->nama_keahlian ?? ''), $search);
            });
        }

        return view('admin.daftar-mahasiswa-keahlian-tambahan', compact('applications'));
    }

    public function approveKeahlianTambahan(Request $request)
    {
        $this->authorizeAccess();

        $id = $request->query('user_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID pengajuan tidak ditemukan.');
        }

        $application = Keahlian_Tambahan::findOrFail($id);

        if ($application->status_pengajuan !== 'Sedang Di Ajukan') {
            return redirect()->back()->with('error', 'Pengajuan tidak valid untuk approval.');
        }

        $application->update([
            'status_pengajuan' => 'Di Terima',
            'is_active' => true,
            'keterangan' => null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan keahlian tambahan berhasil diterima.');
    }

    public function rejectKeahlianTambahan(Request $request)
    {
        $this->authorizeAccess();

        $request->validate(['keterangan' => 'required|string|max:500']);

        $id = $request->query('user_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID pengajuan tidak ditemukan.');
        }

        $application = Keahlian_Tambahan::findOrFail($id);

        if ($application->status_pengajuan !== 'Sedang Di Ajukan') {
            return redirect()->back()->with('error', 'Pengajuan tidak valid untuk rejection.');
        }

        $application->update([
            'status_pengajuan' => 'Di Tolak',
            'is_active' => false,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan keahlian tambahan berhasil ditolak.');
    }

    public function bulkApproveKeahlianTambahan(Request $request)
    {
        $this->authorizeAccess();

        $ids = $request->input('selected_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pengajuan yang dipilih.'
            ], 422);
        }

        $affected = Keahlian_Tambahan::whereIn('id', $ids)
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->update([
                'status_pengajuan' => 'Di Terima',
                'is_active' => true,
                'keterangan' => null,
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$affected} pengajuan keahlian tambahan berhasil disetujui."
        ]);
    }

    public function bulkRejectKeahlianTambahan(Request $request)
    {
        $this->authorizeAccess();

        $request->validate(['keterangan' => 'required|string|max:500']);

        $ids = $request->input('selected_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pengajuan yang dipilih.'
            ], 422);
        }

        $affected = Keahlian_Tambahan::whereIn('id', $ids)
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->update([
                'status_pengajuan' => 'Di Tolak',
                'is_active' => false,
                'keterangan' => $request->keterangan,
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$affected} pengajuan keahlian tambahan berhasil ditolak."
        ]);
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

        $statusOptions = Sertifikat::distinct()->pluck('status_pengajuan')->filter()->values();

        return view('admin.sertifikat', compact(
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

        $sertifikat = Sertifikat::findOrFail($id);
        return view('admin.sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }

    // approve - ambil id dari query parameter
    public function approve(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::findOrFail($id);
        $sertifikat->status_pengajuan = 'Di Terima';
        $sertifikat->is_active = true;
        $sertifikat->save();

        $this->createSertifikatNotification(
            $sertifikat,
            'sertifikat-approved',
            'Sertifikat Disetujui',
            "Sertifikat Anda '{$sertifikat->nama_sertifikat}' telah disetujui oleh admin.",
            'high'
        );

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

        $sertifikat = Sertifikat::findOrFail($id);
        $sertifikat->status_pengajuan = 'Di Tolak';
        $sertifikat->is_active = false;
        $sertifikat->keterangan = $request->keterangan;
        $sertifikat->save();

        $this->createSertifikatNotification(
            $sertifikat,
            'sertifikat-rejected',
            'Sertifikat Ditolak',
            "Sertifikat Anda '{$sertifikat->nama_sertifikat}' telah ditolak. Keterangan: {$request->keterangan}",
            'high'
        );

        return redirect()->back()->with('success', 'Sertifikat ditolak');
    }

    private function createSertifikatNotification(Sertifikat $sertifikat, string $type, string $title, string $message, string $priority = 'high')
    {
        $user = Auth::user();
        $recipientId = $sertifikat->id_mahasiswa;

        if (empty($recipientId)) {
            return;
        }

        $data = [
            'title' => $title,
            'message' => $message,
            'target_type' => 'specific',
            'selected_users' => $recipientId,
            'sender_id' => $user->id,
            'sender_name' => $user->nama_mahasiswa ?? $user->username,
            'sertifikat_id' => $sertifikat->id,
            'sertifikat_name' => $sertifikat->nama_sertifikat,
            'link' => url(app()->getLocale() . '/dashboard'),
        ];

        \App\Http\Controllers\v1\NotificationController::add(
            $type,
            $data,
            $priority
        );
    }

    public function TambahSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')->where('is_active', 1)->where('status_pengajuan', 'Di Terima');

        // Filter pencarian berdasarkan nama mahasiswa
        if ($search) {
            $query->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan angkatan
        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        // Filter berdasarkan keahlian
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        // Ambil data dengan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil data untuk dropdown filter
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        return view('admin.sertifikat.views_create_sertifikat', compact(
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

        $user = User::where('id', $request->user_id)
            ->where('is_active', 1)->where('status_pengajuan', 'Di Terima')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'user_id' => 'User tidak aktif atau tidak ditemukan!'
            ]);
        }

        if ($request->hasFile('link_sertifikat')) {
            $validated['link_sertifikat'] = ImageConversionService::storeWebp($request->file('link_sertifikat'), 'sertifikat');
        }

        Sertifikat::create([
            'id_mahasiswa' => $validated['user_id'],
            'nama_sertifikat' => $validated['nama_sertifikat'],
            'lembaga_penerbit' => $validated['lembaga_penerbit'],
            'tanggal_terbit' => $validated['tanggal_terbit'],
            'expired_date' => $request->has('permanent') ? null : $validated['expired_date'],
            'link_sertifikat' => $validated['link_sertifikat'],

            'status_pengajuan' => 'Di Terima',
            'is_active' => 1,
        ]);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan!');
    }

    // UpdateSertifikat - ambil id dari query parameter
    public function UpdateSertifikat(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.sertifikat.index')
                ->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::findOrFail($id);

        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'lembaga_penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'expired_date' => 'required_without:permanent|nullable|date|after:tanggal_terbit',
            'permanent' => 'nullable|boolean',
            'link_sertifikat' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('link_sertifikat')) {
            if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
                Storage::disk('public')->delete($sertifikat->link_sertifikat);
            }
            $validated['link_sertifikat'] = ImageConversionService::storeWebp($request->file('link_sertifikat'), 'sertifikat');
        } else {
            $validated['link_sertifikat'] = $sertifikat->link_sertifikat;
        }

        $validated['expired_date'] = $request->has('permanent') ? null : $validated['expired_date'];

        $sertifikat->update($validated);

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    // DestroySertifikat - ambil id dari query parameter
    public function DestroySertifikat(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.sertifikat.index')
                ->with('error', 'Sertifikat ID tidak ditemukan');
        }

        $sertifikat = Sertifikat::findOrFail($id);

        // Hapus gambar sertifikat dari storage jika ada
        if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
            Storage::disk('public')->delete($sertifikat->link_sertifikat);
        }

        $sertifikat->delete();

        return redirect()->route('admin.sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    // bulkDestroy - tetap sama karena sudah menggunakan request body
    public function bulkDestroy(Request $request)
    {
        $this->authorizeAccess();
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:sertifikat,id'
        ]);

        try {
            $ids = $request->selected_ids;
            $sertifikats = Sertifikat::whereIn('id', $ids)->get();

            // Delete files from storage
            foreach ($sertifikats as $sertifikat) {
                if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
                    Storage::disk('public')->delete($sertifikat->link_sertifikat);
                }
            }

            // Delete records
            Sertifikat::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' sertifikat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }
    public function bulkApproveSertifikat(Request $request)
    {
        $this->authorizeAccess();

        $ids = $request->input('selected_ids');
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            // ← return JSON agar kompatibel dengan fetch di JS
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sertifikat yang dipilih untuk disetujui.'
            ], 422);
        }

        $affected = Sertifikat::whereIn('id', $ids)
            ->where('status_pengajuan', 'Sedang Di Ajukan')
            ->update([
                'status_pengajuan' => 'Di Terima',
                'is_active' => 1,          // ← pastikan aktif juga
            ]);

        return response()->json([  // ← ganti redirect() dengan JSON
            'success' => true,
            'message' => "{$affected} sertifikat berhasil disetujui."
        ]);
    }
    // For Pages Angkatan
    public function ListAngkatan()
    {
        $this->authorizeAccess();
        $angkatans = Angkatan::all();
        return view('admin.list-angkatan', compact('angkatans'));
    }

    // DetailsAngkatan - ambil id dari query parameter
    public function DetailsAngkatan(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Angkatan ID is required');
        }

        $angkatan = Angkatan::withCount('mahasiswa')->with([
            'mahasiswa' => function ($query) {
                $query->with('jurusan');
            }
        ])->findOrFail($id);

        $jurusans = \App\Models\Jurusan::all();

        return view('admin.angkatan.views_detail_angkatan', compact('angkatan', 'jurusans'));
    }

    public function TambahAngkatan()
    {
        $this->authorizeAccess();
        return view('admin.angkatan.views_create_angkatan');
    }

    public function StoreAngkatan(Request $request)
    {
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255',
            'tahun_masuk' => 'required|date',
            'tahun_keluar' => 'required|date',
        ]);

        Angkatan::create([
            'nama_angkatan' => $validated['nama_angkatan'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'tahun_keluar' => $validated['tahun_keluar']
        ]);

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil ditambahkan!');
    }

    // UpdateAngkatan - ambil id dari query parameter
    public function UpdateAngkatan(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.angkatan.index')
                ->with('error', 'Angkatan ID tidak ditemukan');
        }

        $angkatan = Angkatan::findOrFail($id);

        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255',
            'tahun_masuk' => 'required|date',
            'tahun_keluar' => 'required|date',
        ]);

        $angkatan->update($validated);

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Data Angkatan berhasil diperbarui!');
    }

    // DestroyAngkatan - ambil id dari query parameter
    public function DestroyAngkatan(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.angkatan.index')
                ->with('error', 'Angkatan ID tidak ditemukan');
        }

        $angkatan = Angkatan::findOrFail($id);
        $angkatan->delete();

        return redirect()->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil dihapus.');
    }

    // bulkDestroyAngkatan - perbaiki untuk menerima berbagai format
    public function bulkDestroyAngkatan(Request $request)
    {
        $this->authorizeAccess();

        // Ambil data dari input form (bukan JSON)
        $ids = $request->input('selected_ids');

        // Jika ids adalah string dari input hidden yang diisi oleh JavaScript
        if (is_string($ids)) {
            // Coba parse sebagai JSON dulu
            $decoded = json_decode($ids, true);
            if (is_array($decoded)) {
                $ids = $decoded;
            } else {
                // Jika bukan JSON, mungkin comma separated
                $ids = explode(',', $ids);
            }
        }

        // Filter dan validasi
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.angkatan.index')
                ->with('error', 'Tidak ada data yang dipilih.');
        }

        // Hapus data
        $count = Angkatan::whereIn('id', $ids)->delete();

        return redirect()->route('admin.angkatan.index')
            ->with('success', $count . ' Angkatan berhasil dihapus');
    }

    // For Prodi Pages
    public function ListProdi()
    {
        $this->authorizeAccess();
        $jurusans = Jurusan::all();
        return view('admin.list-prodi', compact('jurusans'));
    }

    // DetailsProdi - ambil id dari query parameter
    public function DetailsProdi(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Prodi ID is required');
        }

        $prodi = Jurusan::withCount('users')
            ->with(['users.angkatan'])
            ->findOrFail($id);

        $angkatans = \App\Models\Angkatan::all();

        return view('admin.prodi.views_detail_prodi', compact('prodi', 'angkatans'));
    }

    public function TambahProdi()
    {
        $this->authorizeAccess();
        return view('admin.prodi.views_create_prodi');
    }

    public function StoreProdi(Request $request)
    {
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
        ]);

        Jurusan::create([
            'nama_jurusan' => $validated['nama_prodi'],
            'created_at' => now()
        ]);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan!');
    }

    // UpdateProdi - ambil id dari query parameter
    public function UpdateProdi(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.prodi.index')
                ->with('error', 'Prodi ID tidak ditemukan');
        }

        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
        ]);

        $jurusan->update([
            'nama_jurusan' => $validated['nama_prodi']
        ]);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Data Prodi berhasil diperbarui!');
    }

    // DestroyProdi - ambil id dari query parameter
    public function DestroyProdi(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.prodi.index')
                ->with('error', 'Prodi ID tidak ditemukan');
        }

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil dihapus.');
    }

    // bulkDestroyProdi - perbaiki untuk menerima berbagai format
    public function bulkDestroyProdi(Request $request)
    {
        $this->authorizeAccess();

        // Ambil data dari input form
        $ids = $request->input('selected_ids');

        // Log untuk debugging
        \Log::info('Bulk Delete Prodi - Raw input:', ['ids' => $ids, 'type' => gettype($ids)]);

        // Jika ids adalah string kosong
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        // Decode JSON jika perlu
        if (is_string($ids)) {
            $decoded = json_decode($ids, true);
            if (is_array($decoded)) {
                $ids = $decoded;
            } else {
                // Jika bukan JSON, mungkin comma separated
                $ids = explode(',', $ids);
            }
        }

        // Pastikan ids adalah array dan bersihkan nilai kosong
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        // Filter nilai yang kosong
        $ids = array_filter($ids, function ($id) {
            return !empty($id);
        });

        // Konversi ke integer
        $ids = array_map('intval', $ids);

        // Jika masih kosong
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data valid yang dipilih.');
        }

        // Hapus data
        $count = Jurusan::whereIn('id_jurusan', $ids)->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', $count . ' Jurusan berhasil dihapus');
    }
    // For Keahlian Pages
    public function ListKeahlian()
    {
        $this->authorizeAccess();
        $keahlians = Keahlian::all();
        return view('admin.list-keahlian', compact('keahlians'));
    }

    // DetailsKeahlian - ambil id dari query parameter
    public function DetailsKeahlian(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Keahlian ID is required');
        }

        $keahlian = Keahlian::withCount('users')
            ->with(['users.angkatan'])
            ->findOrFail($id);

        $angkatans = \App\Models\Angkatan::all();

        return view('admin.keahlian.views_detail_keahlian', compact('keahlian', 'angkatans'));
    }

    public function TambahKeahlian()
    {
        $this->authorizeAccess();
        return view('admin.keahlian.views_create_keahlian');
    }

    public function StoreKeahlian(Request $request)
    {
        $this->authorizeAccess();
        $validated = $request->validate([
            'nama_keahlian' => 'required|string|max:255',
        ]);

        Keahlian::create([
            'nama_keahlian' => $validated['nama_keahlian'],
            'created_at' => now()
        ]);

        return redirect()->route('admin.keahlian.index')
            ->with('success', 'Keahlian berhasil ditambahkan!');
    }

    // UpdateKeahlian - ambil id dari query parameter
    public function UpdateKeahlian(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.keahlian.index')
                ->with('error', 'Keahlian ID tidak ditemukan');
        }

        $keahlian = Keahlian::findOrFail($id);

        $validated = $request->validate([
            'nama_keahlian' => 'required|string|max:255',
        ]);

        $keahlian->update([
            'nama_keahlian' => $validated['nama_keahlian']
        ]);

        return redirect()->route('admin.keahlian.index')
            ->with('success', 'Data Keahlian berhasil diperbarui!');
    }

    // DestroyKeahlian - ambil id dari query parameter
    public function DestroyKeahlian(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.keahlian.index')
                ->with('error', 'Keahlian ID tidak ditemukan');
        }

        $keahlian = Keahlian::findOrFail($id);
        $keahlian->delete();

        return redirect()->route('admin.keahlian.index')
            ->with('success', 'Keahlian berhasil dihapus.');
    }

    // bulkDestroyKeahlian - perbaiki untuk menerima berbagai format
    public function bulkDestroyKeahlian(Request $request)
    {
        $this->authorizeAccess();

        // Ambil data dari berbagai kemungkinan format
        $ids = $request->input('selected_ids');

        // Jika ids adalah string JSON, decode dulu
        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        // Jika ids masih string biasa (comma separated)
        if (is_string($ids) && str_contains($ids, ',')) {
            $ids = explode(',', $ids);
        }

        // Jika ids adalah array tapi berisi string JSON
        if (is_array($ids) && count($ids) === 1 && is_string($ids[0]) && str_starts_with($ids[0], '[')) {
            $ids = json_decode($ids[0], true);
        }

        // Pastikan ids adalah array
        if (empty($ids) || !is_array($ids)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipilih.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $count = Keahlian::whereIn('id_keahlian', $ids)->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $count . ' Keahlian berhasil dihapus'
            ]);
        }

        return redirect()->back()->with('success', $count . ' Keahlian berhasil dihapus');
    }

    //For Projects Pages
    public function projects(Request $request)
    {
        $this->authorizeAccess();
        
        $query = Project::with(['mahasiswa', 'leader']);
        
        // Filter berdasarkan nama project (search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('isi_content->nama_project', 'like', "%{$search}%")
                ->orWhereHas('mahasiswa', function($subQ) use ($search) {
                    $subQ->where('nama_mahasiswa', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })
                ->orWhereHas('leader', function($subQ) use ($search) {
                    $subQ->where('nama_mahasiswa', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $status = $request->status;
            $today = \Carbon\Carbon::today();
            
            $query->where(function($q) use ($status, $today) {
                if ($status === 'Sedang Berjalan') {
                    $q->where('tanggal_mulai', '<=', $today)
                    ->where(function($sub) use ($today) {
                        $sub->where('tanggal_akhir', '>=', $today)
                            ->orWhereNull('tanggal_akhir');
                    });
                } elseif ($status === 'Selesai') {
                    $q->where('tanggal_akhir', '<', $today);
                } elseif ($status === 'Akan Datang') {
                    $q->where('tanggal_mulai', '>', $today);
                }
            });
        }
        
        $projects = $query->latest()->paginate(12)->withQueryString();
        
        return view('admin.project', compact('projects'));
    }

    public function TambahProjects(Request $request)
    {
        $this->authorizeAccess();
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $query = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima');

        // Filter pencarian berdasarkan nama mahasiswa, username, atau email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan angkatan
        if ($angkatan) {
            $query->where('id_angkatan', $angkatan);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $query->where('id_jurusan', $jurusan);
        }

        // Filter berdasarkan keahlian
        if ($keahlian) {
            $query->where('id_keahlian', $keahlian);
        }

        // Ambil data dengan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil semua user untuk JavaScript (untuk mendukung multiple members dari semua halaman)
        $allUsers = $query->orderBy('created_at', 'desc')->get();

        // Ambil data untuk dropdown filter
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        if ($request->ajax()) {
            // Return JSON for AJAX requests
            $userListHtml = '';
            if ($users->count() > 0) {
                foreach ($users as $user) {
                    $userListHtml .= '
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                ' . ($user->photo_profile ?
                        '<img src="/storage/' . $user->photo_profile . '" class="w-10 h-10 rounded-full object-cover">' :
                        '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold">' . strtoupper(substr($user->nama_mahasiswa, 0, 1)) . '</span>
                                    </div>'
                    ) . '
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">' . $user->nama_mahasiswa . '</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none" title="' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" onchange="updateUserRole(this, ' . $user->id . ', this.value)">
                                    <option value="" data-translate="choose_role" data-translate-page="dosen_add_pjt">-- Pilih Role --</option>
                                    <option value="owner" data-translate="owner_role" data-translate-page="dosen_add_pjt">Owner</option>
                                    <option value="leader" data-translate="leader_role" data-translate-page="dosen_add_pjt">Leader</option>
                                    <option value="member" data-translate="member_role" data-translate-page="dosen_add_pjt">Member</option>
                                </select>
                            </div>
                        </div>
                    ';
                }
            } else {
                $userListHtml = '<div class="text-center py-10 text-gray-500 dark:text-gray-400" data-translate="no_students_found" data-translate-page="dosen_add_pjt">Tidak ada mahasiswa yang sesuai filter.</div>';
            }

            $paginationHtml = $users->render('vendor.pagination.custom_ajax', ['groupName' => 'admin_project_user_selection'])->toHtml();

            return response()->json([
                'userListHtml' => $userListHtml,
                'paginationHtml' => $paginationHtml,
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
            ]);
        }

        return view('admin.projects.views_create_project', compact(
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

    // EditProjects - ambil id dari query parameter
    public function EditProjects(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Project ID is required');
        }

        // Get search and filter inputs
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');
        $jurusan = $request->input('jurusan');
        $keahlian = $request->input('keahlian');

        // Query dasar untuk mendapatkan user dengan role mahasiswa beserta relasinya
        $baseQuery = User::with(['jurusan', 'angkatan', 'keahlian'])
            ->where('role', 'mahasiswa')
            ->where('status_pengajuan', 'Di Terima');

        // Filter pencarian berdasarkan nama mahasiswa, username, atau email
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan angkatan
        if ($angkatan) {
            $baseQuery->where('id_angkatan', $angkatan);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $baseQuery->where('id_jurusan', $jurusan);
        }

        // Filter berdasarkan keahlian
        if ($keahlian) {
            $baseQuery->where('id_keahlian', $keahlian);
        }

        // Ambil semua user untuk JavaScript (untuk mendukung multiple members dari semua halaman)
        $allUsers = $baseQuery->orderBy('created_at', 'desc')->get();

        // Ambil data untuk dropdown filter
        $angkatans = Angkatan::all();
        $jurusans = Jurusan::all();
        $keahlians = Keahlian::all();

        // Get project data
        $project = Project::with(['members', 'tasks'])
            ->where('id', $id)
            ->firstOrFail();

        if ($request->ajax()) {
            // Clone query untuk pagination (agar tidak mempengaruhi $allUsers)
            $paginatedQuery = clone $baseQuery;
            $users = $paginatedQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

            // Return JSON for AJAX requests
            $userListHtml = '';
            if ($users->count() > 0) {
                foreach ($users as $user) {
                    $userListHtml .= '
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                ' . ($user->photo_profile ?
                        '<img src="/storage/' . $user->photo_profile . '" class="w-10 h-10 rounded-full object-cover">' :
                        '<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold">' . strtoupper(substr($user->nama_mahasiswa, 0, 1)) . '</span>
                                    </div>'
                    ) . '
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">' . $user->nama_mahasiswa . '</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[145px] md:max-w-none" title="' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" onchange="updateUserRole(this, ' . $user->id . ', this.value)">
                                    <option value="" data-translate="choose_role" data-translate-page="dosen_add_pjt">-- Pilih Role --</option>
                                    <option value="owner" data-translate="owner_role" data-translate-page="dosen_add_pjt">Owner</option>
                                    <option value="leader" data-translate="leader_role" data-translate-page="dosen_add_pjt">Leader</option>
                                    <option value="member" data-translate="member_role" data-translate-page="dosen_add_pjt">Member</option>
                                </select>
                            </div>
                        </div>
                    ';
                }
            } else {
                $userListHtml = '<div class="text-center py-10 text-gray-500 dark:text-gray-400" data-translate="no_students_found" data-translate-page="dosen_add_pjt">Tidak ada mahasiswa yang sesuai filter.</div>';
            }

            $paginationHtml = $users->render('vendor.pagination.custom_ajax', ['groupName' => 'admin_project_user_selection'])->toHtml();

            return response()->json([
                'userListHtml' => $userListHtml,
                'paginationHtml' => $paginationHtml,
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
            ]);
        }

        // ================= NON AJAX =================
        // TANPA PAGINATION
        $users = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.projects.views_edit_project', compact(
            'project',
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

    // Endpoint AJAX untuk cek duplikat nama project secara real-time dari form admin
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

        // Merge dan filter members
        $request->merge([
            'members' => collect(explode(',', $request->members ?? ''))
                ->filter(fn($id) => !empty($id))
                ->values()
                ->all(),
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) && !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);

        // Validasi
        $validated = $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:500',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'owner' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima', // Owner (id_mahasiswa)
            'leader' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima', // Leader (leader_id)
            'members' => 'nullable|array',
            'members.*' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks' => 'nullable|array',
            'tasks.*.user_id' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks.*.name_task' => 'nullable|string|max:255'
        ]);

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

        // Set owner dan leader
        $ownerId = $request->owner ?: null;
        $leaderId = $request->leader ?: null;

        // Jika leader tidak diisi, gunakan owner sebagai leader
        if (!$leaderId && $ownerId) {
            $leaderId = $ownerId;
        }

        // Buat project baru
        $project = Project::create([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => $ownerId,
            'leader_id' => $leaderId,
        ]);


        $members = collect($request->members ?? [])
            ->filter()
            ->reject(fn($id) => $id == $ownerId || $id == $leaderId)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();


        if (!empty($members)) {
            $project->members()->attach($members);
        }


        $tasks = collect($request->input('tasks', []))->filter(function ($task) {
            return is_array($task)
                && !empty($task['user_id'])
                && !empty(trim($task['name_task'] ?? ''));
        })->values()->all();

        if (!empty($tasks)) {
            $this->createProjectTasks($project, $tasks);
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil ditambahkan!')
            ->with('clear_local_storage', true);
    }

    // UpdateProject - support URL query parameter ?id=123
    public function UpdateProject(Request $request)
    {
        $this->authorizeAccess();

        // Ambil id dari query parameter
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'Project ID tidak ditemukan');
        }

        $project = Project::findOrFail($id);

        // Sanitize members input - support string "1,2,3" atau array
        $membersInput = $request->input('members', '');

        if (is_string($membersInput) && !empty($membersInput)) {
            $membersArray = explode(',', $membersInput);
            $request->merge([
                'members' => array_filter($membersArray)
            ]);
        } elseif (is_array($membersInput)) {
            $request->merge([
                'members' => array_filter($membersInput)
            ]);
        } else {
            $request->merge([
                'members' => []
            ]);
        }

        // Filter tasks
        $request->merge([
            'tasks' => collect($request->input('tasks', []))
                ->filter(fn($task) => is_array($task) && (
                    !empty($task['user_id']) &&
                    !empty(trim($task['name_task'] ?? ''))
                ))
                ->values()
                ->all()
        ]);

        $validated = $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'link_project' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'link_github' => 'nullable|url|max:500',
            'link_video' => 'nullable|url|max:500',
            'owner' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'leader' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks' => 'nullable|array',
            'tasks.*.id' => 'sometimes|nullable|integer|exists:project_tasks,id',
            'tasks.*.user_id' => 'nullable|exists:users,id,role,mahasiswa,status_pengajuan,Di Terima',
            'tasks.*.name_task' => 'nullable|string|max:255',
            'is_collaborative' => 'nullable|boolean'
        ]);

        if ($this->isDuplicateProjectName($request->nama_project, (int) $project->id)) {
            return back()
                ->withInput()
                ->withErrors(['nama_project' => "Nama project \"{$request->nama_project}\" sudah digunakan. Silakan gunakan nama lain."]);
        }

        // Prepare content
        $content = [];

        if ($request->filled('nama_project'))
            $content['nama_project'] = $request->nama_project;

        if ($request->filled('deskripsi'))
            $content['deskripsi'] = $request->deskripsi;

        if ($request->filled('link_project'))
            $content['link_project'] = $request->link_project;

        if ($request->filled('link_github'))
            $content['link_github'] = $request->link_github;

        if ($request->filled('link_video'))
            $content['link_video'] = $request->link_video;

        if (empty($content)) {
            return back()
                ->withInput()
                ->withErrors([
                    'project' => 'Minimal isi salah satu field (nama_project, deskripsi, atau link)'
                ]);
        }

        $ownerId = $request->owner ?: null;
        $leaderId = $request->leader ?: null;
        $isCollaborative = $request->boolean('is_collaborative', true);

        // Jika collaborative tapi leader kosong
        if ($isCollaborative && !$leaderId && $ownerId) {
            $leaderId = $ownerId;
        }

        // Jika non collaborative
        if (!$isCollaborative && $ownerId) {
            $leaderId = $ownerId;
        }

        // Update project
        $project->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'isi_content' => $content,
            'id_mahasiswa' => $ownerId,
            'leader_id' => $leaderId,
        ]);

        // Handle members
        $members = collect($request->members ?? [])
            ->filter()
            ->reject(
                fn($memberId) =>
                $memberId == $ownerId ||
                $memberId == $leaderId
            )
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (!$isCollaborative) {
            $members = [];
        }

        $project->members()->sync($members);

        // Handle tasks
        $submittedTasks = collect($request->input('tasks', []))
            ->filter(
                fn($task) =>
                !empty($task['user_id']) &&
                !empty(trim($task['name_task'] ?? ''))
            )
            ->values()
            ->all();

        if (!empty($submittedTasks)) {

            $savedTaskIds = [];

            foreach ($submittedTasks as $taskData) {

                if (!empty($taskData['id'])) {

                    $task = ProjectTask::find($taskData['id']);

                    if ($task && $task->project_id == $project->id) {

                        $task->update([
                            'user_id' => $taskData['user_id'],
                            'name_task' => $taskData['name_task'],
                        ]);

                        $savedTaskIds[] = $task->id;

                    } else {

                        $newTask = $project->tasks()->create([
                            'user_id' => $taskData['user_id'],
                            'name_task' => $taskData['name_task'],
                        ]);

                        $savedTaskIds[] = $newTask->id;
                    }

                } else {

                    $newTask = $project->tasks()->create([
                        'user_id' => $taskData['user_id'],
                        'name_task' => $taskData['name_task'],
                    ]);

                    $savedTaskIds[] = $newTask->id;
                }
            }

            ProjectTask::where('project_id', $project->id)
                ->whereNotIn('id', $savedTaskIds)
                ->delete();

        } else {

            ProjectTask::where('project_id', $project->id)->delete();
        }

        // Validasi task user masih bagian project
        $allowedUserIds = collect([$ownerId, $leaderId])
            ->merge($members)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($allowedUserIds)) {
            ProjectTask::where('project_id', $project->id)
                ->whereNotIn('user_id', $allowedUserIds)
                ->delete();
        } else {
            ProjectTask::where('project_id', $project->id)->delete();
        }

        // Jika solo project, task hanya owner
        if (!$isCollaborative && $ownerId) {
            ProjectTask::where('project_id', $project->id)
                ->where('user_id', '!=', $ownerId)
                ->delete();
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil diperbarui!');
    }

    // DestroyProject - ambil id dari query parameter
    public function DestroyProject(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'Project ID tidak ditemukan');
        }

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project Mahasiswa berhasil dihapus!');
    }

    public function bulkDestroyProject(Request $request)
    {
        $this->authorizeAccess();

        // Ambil dari input
        $selectedIds = $request->input('selected_ids', []);

        // Log untuk debugging
        \Log::info('Bulk Delete - Raw input:', ['selected_ids' => $selectedIds, 'type' => gettype($selectedIds)]);

        // Jika string, coba decode JSON
        if (is_string($selectedIds)) {
            $decoded = json_decode($selectedIds, true);
            if (is_array($decoded)) {
                $selectedIds = $decoded;
            } else {
                // Jika bukan JSON, coba explode comma
                $selectedIds = explode(',', $selectedIds);
            }
        }

        // Pastikan $selectedIds adalah array
        if (!is_array($selectedIds)) {
            $selectedIds = [];
        }

        // Filter dan konversi ke integer (hanya jika array tidak kosong)
        $ids = [];
        if (!empty($selectedIds)) {
            $ids = array_values(array_filter(array_map('intval', $selectedIds)));
        }

        // Jika masih kosong
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada project terpilih untuk dihapus.');
        }

        Project::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Project berhasil dihapus');
    }

    // Notifikasi Page
    public function notifications(Request $request)
    {
        $this->authorizeAccess();

        $search = $request->input('search');
        $type = $request->input('type');
        $priority = $request->input('priority');
        $read = $request->input('read');

        $query = Notification::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', '%' . $search . '%')
                    ->orWhereJsonContains('data->title', $search)
                    ->orWhereJsonContains('data->message', $search);
            });
        }

        if ($type && $type !== 'Semua') {
            $query->where('type', $type);
        }

        if ($priority && $priority !== 'Semua') {
            $query->where('priority', $priority);
        }

        if ($read !== null && $read !== '') {
            $query->where('read', $read === '1');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $types = Notification::distinct()->pluck('type')->filter()->values();
        $priorities = ['normal', 'high', 'low'];

        return view('admin.notifikasi.views-notifications', compact(
            'notifications',
            'types',
            'priorities',
            'search',
            'type',
            'priority',
            'read'
        ));
    }

    // For Notifications CRUD
    public function ViewAddNotification()
    {
        $this->authorizeAccess();
        return view('admin.notifikasi.views_create_notification');
    }

    public function StoreNotification(Request $request)
    {
        $this->authorizeAccess();

        if ($request->target_type !== 'specific') {
            $request->request->remove('selected_users');
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:normal,high,low',
            'target_type' => 'required|in:all,role,specific',
            'target_role' => 'required_if:target_type,role|in:mahasiswa,dosen,admin',
            'selected_users' => 'required_if:target_type,specific|json',
        ]);

        $data = [
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target_type' => $validated['target_type'],
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->nama_mahasiswa ?? Auth::user()->username,
        ];

        if ($validated['target_type'] === 'role') {
            $data['target_role'] = $validated['target_role'];
        } elseif ($validated['target_type'] === 'specific') {
            $data['selected_users'] = json_decode($validated['selected_users'], true);
        }

        // Send notification
        $this->sendAdminNotification($validated['type'], $data, $validated['priority']);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dikirim!');
    }

    private function sendAdminNotification($type, $data, $priority = 'normal')
    {
        return NotificationController::add($type, $data, $priority);
    }

    public function EditNotification(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            abort(404, 'Notification ID is required');
        }

        $notification = Notification::findOrFail($id);
        return view('admin.notifikasi.views_edit_notification', compact('notification'));
    }

    public function UpdateNotification(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Notification ID tidak ditemukan');
        }

        $notification = Notification::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:normal,high,low',
            'read' => 'boolean',
        ]);

        $data = $notification->data;
        $data['title'] = $validated['title'];
        $data['message'] = $validated['message'];

        $notification->update([
            'type' => $validated['type'],
            'data' => $data,
            'priority' => $validated['priority'],
            'read' => $request->has('read') ? $validated['read'] : $notification->read,
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil diperbarui!');
    }

    public function DestroyNotification(Request $request)
    {
        $this->authorizeAccess();
        $id = $request->query('id');

        if (!$id) {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Notification ID tidak ditemukan');
        }

        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function bulkDestroyNotification(Request $request)
    {
        $this->authorizeAccess();

        $ids = $request->input('selected_ids');

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()
                ->with('error', 'Tidak ada notifikasi yang dipilih untuk dihapus.');
        }

        $count = Notification::whereIn('id', $ids)->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', "{$count} notifikasi berhasil dihapus.");
    }

    // AJAX method to load users for notification targeting
    public function loadUsersForNotification(Request $request)
    {
        $this->authorizeAccess();

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = User::where('id', '!=', Auth::id()) // Exclude current admin
            ->where('is_active', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('nama_mahasiswa')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'users' => $users->items(),
            'hasMore' => $users->hasMorePages(),
            'currentPage' => $users->currentPage(),
            'total' => $users->total(),
        ]);
    }
}