<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\LearningCorner;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class LearningCornerController extends Controller
{
    public function index()
    {
        $entries = LearningCorner::where('id_mahasiswa', Auth::id())
            ->latest('tanggal')
            ->get();

        return view('learning-corner.views-learning-corner', compact('entries'));
    }

    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);

        return view('learning-corner.views-create-learning-corner', compact('project'));
    } 
    public function store(Request $request)
{
    $validated = $request->validate([
        'judul'           => 'required|string|max:255',
        'items'           => 'nullable|array',
        'items.*.type'    => 'required|in:text,image,link',
        'items.*.content' => 'required_if:items.*.type,text,link|string|nullable',
        'items.*.file'    => 'required_if:items.*.type,image|image|mimes:jpg,jpeg,png,gif|max:5120', // 5MB
    ]);

    $content = [
        ['type' => 'title', 'content' => $validated['judul']],
    ];

    if (!empty($validated['items'])) {
        foreach ($validated['items'] as $index => $item) {
            $processedItem = [
                'type'    => $item['type'],
                'content' => $item['content'] ?? null,
            ];

            if ($item['type'] === 'image' && $request->hasFile("items.$index.file")) {
                $file = $request->file("items.$index.file");
                $path = $file->store('learning-corner/images', 'public');
                $processedItem['content'] = $path; // simpan path, bukan URL langsung
            }

            $content[] = $processedItem;
        }
    }

    LearningCorner::create([
        'id_mahasiswa' => Auth::id(),
        'project_id'   => $request->project_id,
        'content'      => $content,
        'tanggal'      => now(),
    ]);

    return redirect()->route('project.show', $request->project_id)
        ->with('success', 'Learning Corner berhasil ditambahkan!');
}

    public function edit(LearningCorner $learningCorner)
    {
        $this->authorizeEntry($learningCorner);

        $judul = $learningCorner->judul;
        $items = array_values(   // reset index array
            array_filter($learningCorner->content, fn($item) => ($item['type'] ?? '') !== 'title')
        );

        return view('learning-corner.views-edit-learning-corner', compact('learningCorner', 'judul', 'items'));
    }

    public function update(Request $request, LearningCorner $learningCorner)
{
    $this->authorizeEntry($learningCorner);

    $validated = $request->validate([
        'judul'               => 'required|string|max:255',
        'items'               => 'nullable|array',
        'items.*.type'        => 'required|in:text,image,link',
        'items.*.content'     => 'nullable|string',               // lama / link / text
        'items.*.image_file'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
    ]);

    $content = [
        ['type' => 'title', 'content' => $validated['judul']],
    ];

    if (!empty($validated['items'])) {
        foreach ($validated['items'] as $idx => $item) {
            if ($item['type'] === 'image' && $request->hasFile("items.$idx.image_file")) {
                $path = $request->file("items.$idx.image_file")->store('learning-corner', 'public');
                $item['content'] = $path; // simpan path baru
            }
            // jika tidak upload gambar baru → content lama tetap dipakai (dari hidden input)
            $content[] = $item;
        }
    }

    $learningCorner->update([
        'content' => $content,
        'tanggal' => now(),
    ]);

    return redirect()->route('project.index')
        ->with('success', 'Learning Corner berhasil diperbarui!');
}

    public function destroy(LearningCorner $learningCorner)
    {
        $this->authorizeEntry($learningCorner);

        $learningCorner->delete();

        return redirect()->route('project.index')
            ->with('success', 'Learning Corner berhasil dihapus.');
    }

    private function authorizeEntry(LearningCorner $entry): void
    {
        if ($entry->project->id_mahasiswa !== Auth::id())
            abort(403, 'Aksi tidak diizinkan.');
        }
}