<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\LearningCorner;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageConversionService;


class LearningCornerController extends Controller
{
    public function index()
    {
        $entries = LearningCorner::with('mahasiswa', 'project')
            ->where('id_mahasiswa', Auth::id())
            ->latest('tanggal')
            ->get();

        return view('admin.learning-corner', compact('entries'));
    }

    public function create($projectId)
    {
        $project = Project::with(['owner', 'leader', 'members'])
            ->findOrFail($projectId);

        // Cek apakah user terlibat di project (owner/leader/member)
        $userId = Auth::id();
        if (
            $project->id_mahasiswa !== $userId &&
            $project->leader_id !== $userId &&
            !$project->members()->where('user_id', $userId)->exists()
        ) {
            abort(403, 'Anda tidak terlibat dalam project ini.');
        }

        return view('learning-corner.views-create-learning-corner', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        // Cek apakah user boleh create di project ini
        $userId = Auth::id();
        if (
            $project->id_mahasiswa !== $userId &&
            $project->leader_id !== $userId &&
            !$project->members()->where('user_id', $userId)->exists()
        ) {
            abort(403, 'Anda tidak terlibat dalam project ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:text,image,link',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
        ];

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $index => $item) {
                $processed = [
                    'type' => $item['type'],
                    'content' => $item['content'] ?? null,
                ];

                if ($item['type'] === 'image' && $request->hasFile("items.$index.file")) {
                    $path = ImageConversionService::storeWebp($request->file("items.$index.file"), 'learning-corner/images');
                    $processed['content'] = $path;
                }

                $content[] = $processed;
            }
        }

        LearningCorner::create([
            'id_mahasiswa' => Auth::id(),
            'project_id' => $project->id,
            'content' => $content,
            'tanggal' => now(),
        ]);

        return redirect()->route('project.show', $project->id)
            ->with('success', 'Learning Corner berhasil ditambahkan!');
    }

    public function edit(LearningCorner $learningCorner)
    {
        $this->authorizeManage($learningCorner);

        $judul = $learningCorner->judul;

        $items = array_values(
            array_filter($learningCorner->content ?? [], fn($i) => ($i['type'] ?? '') !== 'title')
        );

        return view('learning-corner.views-edit-learning-corner', compact('learningCorner', 'judul', 'items'));
    }

    public function update(Request $request, LearningCorner $learningCorner)
    {
        $this->authorizeManage($learningCorner);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:text,image,link',
            'items.*.content' => 'nullable|string',
            'items.*.image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Kumpulkan path gambar dari content lama
        $oldImages = [];
        foreach ($learningCorner->content ?? [] as $item) {
            if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                $oldImages[] = $item['content'];
            }
        }

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
        ];

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $idx => $item) {
                $processed = $item;

                if ($item['type'] === 'image' && $request->hasFile("items.$idx.image_file")) {
                    // Hapus gambar lama jika ada
                    if (!empty($item['content']) && Storage::disk('public')->exists($item['content'])) {
                        Storage::disk('public')->delete($item['content']);
                    }
                    $path = ImageConversionService::storeWebp($request->file("items.$idx.image_file"), 'learning-corner/images');
                    $processed['content'] = $path;
                }

                $content[] = $processed;
            }
        }

        // Kumpulkan path gambar dari content baru
        $newImages = [];
        foreach ($content as $item) {
            if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                $newImages[] = $item['content'];
            }
        }

        // Hapus gambar yang tidak lagi digunakan
        $imagesToDelete = array_diff($oldImages, $newImages);
        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $learningCorner->update([
            'content' => $content,
            'tanggal' => now(),
        ]);

        return redirect()->route('project.show', $learningCorner->project_id)
            ->with('success', 'Learning Corner berhasil diperbarui!');
    }

    public function destroy(LearningCorner $learningCorner)
    {
        $this->authorizeManage($learningCorner);

        // Hapus semua gambar yang terkait
        foreach ($learningCorner->content ?? [] as $item) {
            if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                Storage::disk('public')->delete($item['content']);
            }
        }

        $learningCorner->delete();

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.projects.index')
                ->with('success', 'Learning Corner berhasil dihapus.');
        }

        if (Auth::user()->role === 'dosen') {
            return redirect()->route('dosen.projects.index')
                ->with('success', 'Learning Corner berhasil dihapus.');
        }

        return redirect()->route('project.show', $learningCorner->project_id)
            ->with('success', 'Learning Corner berhasil dihapus.');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $learningCorners = LearningCorner::whereIn('id_learning_corner', $ids)->get();

        foreach ($learningCorners as $learningCorner) {
            // Cek permission untuk setiap entry
            if (!$learningCorner->canManage(Auth::user())) {
                continue; // Lewati yang tidak punya akses
            }

            // Hapus semua gambar
            foreach ($learningCorner->content ?? [] as $item) {
                if (($item['type'] ?? '') === 'image' && !empty($item['content'])) {
                    Storage::disk('public')->delete($item['content']);
                }
            }

            // Hapus record
            $learningCorner->delete();
        }

        return back()->with('success', 'Learning Corner berhasil dihapus.');
    }

    public function learning_corner_user()
    {
        $entries = LearningCorner::with(['mahasiswa', 'project'])
            ->where('id_mahasiswa', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        return view('learning-corner.views-learning-corner-user', compact('entries'));
    }

    private function authorizeManage(LearningCorner $entry): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola entri ini.');
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'dosen' && $entry->canManageDosen($user)) {
            return;
        }

        if (!$entry->canManage($user)) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola entri ini.');
        }
    }
}