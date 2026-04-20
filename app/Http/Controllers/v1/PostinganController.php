<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Postingan;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Auth;

class PostinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $postingan = Postingan::with('user', 'komentar.user', 'likes')
            ->where('id_user', auth()->id())
            ->latest()
            ->paginate(10);

        return view('postingan.postingan_card', compact('postingan'));
    }

    public function create() 
    {
        return view('postingan.views_create_postingan');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:image,link',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
            ['type' => 'description', 'content' => $validated['deskripsi']],
        ];

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $index => $item) {
                $processed = [
                    'type' => $item['type'],
                    'content' => $item['content'] ?? null,
                ];

                if ($item['type'] === 'image' && $request->hasFile("items.$index.file")) {
                    $path = ImageConversionService::storeWebp($request->file("items.$index.file"), 'postingan/images');
                    $processed['content'] = $path;
                }

                $content[] = $processed;
            }
        }

        Postingan::create([
            'id_user' => Auth::id(),
            'content' => $content,
            'tanggal' => now(),
        ]);

        return redirect()->route('postingan.index')->with('success', 'Postingan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Get ID from query parameter
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Postingan ID is required');
        }

        $postingan = Postingan::with(['user', 'komentar.user', 'likes'])
            ->where('id_postingan', $id)
            ->firstOrFail();

        return view('postingan.views_detail_postingan', compact('postingan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Postingan ID is required');
        }
        
        $postingan = Postingan::where('id_postingan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        return view('postingan.views_edit_postingan', compact('postingan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Postingan ID is required');
        }
        
        $postingan = Postingan::where('id_postingan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:image,link',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Extract judul from content for validation
        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
            ['type' => 'description', 'content' => $validated['deskripsi']],
        ];

        $oldImagePaths = collect($postingan->content ?? [])
            ->where('type', 'image')
            ->pluck('content')
            ->filter()
            ->values()
            ->all();

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $index => $item) {
                $processed = [
                    'type' => $item['type'],
                    'content' => $item['content'] ?? null,
                ];

                if ($item['type'] === 'image' && $request->hasFile("items.$index.file")) {
                    $path = ImageConversionService::storeWebp($request->file("items.$index.file"), 'postingan/images');
                    $processed['content'] = $path;
                }

                $content[] = $processed;
            }
        }

        $newImagePaths = collect($content)
            ->where('type', 'image')
            ->pluck('content')
            ->filter()
            ->values()
            ->all();

        $removedImagePaths = array_diff($oldImagePaths, $newImagePaths);

        foreach ($removedImagePaths as $removedPath) {
            Storage::disk('public')->delete($removedPath);
        }

        $postingan->update([
            'content' => $content,
        ]);

        return redirect()->route('postingan.index')->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Postingan ID is required');
        }
        
        $postingan = Postingan::where('id_postingan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // Delete associated images
        if (is_array($postingan->content)) {
            foreach ($postingan->content as $item) {
                if (isset($item['type']) && $item['type'] === 'image' && isset($item['content'])) {
                    Storage::disk('public')->delete($item['content']);
                }
            }
        }

        $postingan->delete();

        return redirect()->route('postingan.index')->with('success', 'Postingan berhasil dihapus!');
    }
}