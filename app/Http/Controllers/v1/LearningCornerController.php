<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\LearningCorner;
use Illuminate\Http\Request;
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

    public function create()
    {
        return view('learning-corner.views-create-learning-corner');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'items'          => 'nullable|array',
            'items.*.type'   => 'required|in:text,image,link',
            'items.*.content'=> 'required|string',
        ]);

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
        ];

        if (!empty($validated['items'])) {
            $content = array_merge($content, $validated['items']);
        }

        LearningCorner::create([
            'id_mahasiswa' => Auth::id(),
            'content' => $content,
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('learning-corner.index')
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
            'judul'          => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'items'          => 'nullable|array',
            'items.*.type'   => 'required|in:text,image,link',
            'items.*.content'=> 'required|string',
        ]);

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
        ];

        if (!empty($validated['items'])) {
            $content = array_merge($content, $validated['items']);
        }

        $learningCorner->update([
            'content' => $content,
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('learning-corner.index')
            ->with('success', 'Learning Corner berhasil diperbarui!');
    }

    public function destroy(LearningCorner $learningCorner)
    {
        $this->authorizeEntry($learningCorner);

        $learningCorner->delete();

        return redirect()->route('learning-corner.index')
            ->with('success', 'Learning Corner berhasil dihapus.');
    }

    private function authorizeEntry(LearningCorner $entry): void
    {
        if ($entry->id_mahasiswa !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }
    }
}