<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Postingan;
use App\Models\Game;
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
        $user = auth()->user();
        $query = Postingan::with('user', 'komentar.user', 'likes');

        if ($user->role === 'mahasiswa') {
            $query->where('id_user', $user->id);
        } elseif ($user->role === 'dosen') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('role', 'mahasiswa')
                  ->where('id_angkatan', $user->id_angkatan)
                  ->where('id_jurusan', $user->id_jurusan)
                  ->where('id_keahlian', $user->id_keahlian);
            });
        } elseif ($user->role === 'admin') {
            // Admin can see all postings
        }

        $postingan = $query->latest()->paginate(10);

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
            'game_enabled' => 'nullable|in:on,1,true,0',
            'game_name' => 'nullable|string|max:100',
            'game_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $content = [
            ['type' => 'title', 'content' => $validated['judul']],
            ['type' => 'description', 'content' => $validated['deskripsi']],
        ];

        // Use the raw request items to preserve array keys for uploaded files
        $rawItems = $request->input('items', []);
        if (!empty($rawItems) && is_array($rawItems)) {
            foreach ($rawItems as $index => $item) {
                $processed = [
                    'type' => $item['type'] ?? null,
                    'content' => $item['content'] ?? null,
                ];

                if (($processed['type'] ?? '') === 'image' && $request->hasFile("items.$index.file")) {
                    $path = ImageConversionService::storeWebp($request->file("items.$index.file"), 'postingan/images');
                    $processed['content'] = $path;
                }

                // only add if type exists
                if (!empty($processed['type'])) {
                    // Skip image items without uploaded content
                    if ($processed['type'] === 'image' && empty($processed['content'])) {
                        continue;
                    }
                    $content[] = $processed;
                }
            }
        }

        // Handle optional game thumbnail upload
        if ($request->hasFile('game_thumbnail')) {
            $thumbPath = ImageConversionService::storeWebp($request->file('game_thumbnail'), 'postingan/game_thumbnails');
            $content[] = ['type' => 'game_thumbnail', 'content' => $thumbPath];
        }

        $post = Postingan::create([
            'id_user' => Auth::id(),
            'content' => $content,
            'tanggal' => now(),
        ]);

        // If user selected to include a game, create a game record linked to this posting
        if ($request->filled('game_enabled')) {
            $gameName = $request->input('game_name') ?: 'Matematika';
            Game::create([
                'id_postingan' => $post->id_postingan,
                'id_user' => Auth::id(),
                'game_name' => $gameName,
                'score' => '0',
                'playing_time' => '0',
                'is_active' => true,
            ]);
        }

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

        $user = auth()->user();
        $query = Postingan::with(['user', 'komentar.user', 'likes']);

        if ($user->role === 'mahasiswa') {
            $query->where('id_user', $user->id);
        } elseif ($user->role === 'dosen') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('role', 'mahasiswa')
                  ->where('id_angkatan', $user->id_angkatan)
                  ->where('id_jurusan', $user->id_jurusan)
                  ->where('id_keahlian', $user->id_keahlian);
            });
        } elseif ($user->role === 'admin') {
            // Admin can see all
        }

        $postingan = $query->where('id_postingan', $id)->firstOrFail();

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

        $user = auth()->user();
        $query = Postingan::query();

        if ($user->role === 'mahasiswa') {
            $query->where('id_user', $user->id);
        } elseif ($user->role === 'dosen') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('role', 'mahasiswa')
                  ->where('id_angkatan', $user->id_angkatan)
                  ->where('id_jurusan', $user->id_jurusan)
                  ->where('id_keahlian', $user->id_keahlian);
            });
        } elseif ($user->role === 'admin') {
            // Admin can edit all
        }

        $postingan = $query->where('id_postingan', $id)->firstOrFail();

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

        $user = auth()->user();
        $query = Postingan::query();

        if ($user->role === 'mahasiswa') {
            $query->where('id_user', $user->id);
        } elseif ($user->role === 'dosen') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('role', 'mahasiswa')
                  ->where('id_angkatan', $user->id_angkatan)
                  ->where('id_jurusan', $user->id_jurusan)
                  ->where('id_keahlian', $user->id_keahlian);
            });
        } elseif ($user->role === 'admin') {
            // Admin can update all
        }

        $postingan = $query->where('id_postingan', $id)->firstOrFail();

        $isAllowedGame = in_array($user->role, ['admin', 'dosen']);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:image,link',
            'items.*.content' => 'nullable|string',
            'items.*.file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'game_enabled' => 'nullable|in:on,1,true,0',
            'game_name' => 'nullable|string|max:100',
            'game_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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

        // find existing game and thumbnail in current posting content
        $existingGame = Game::where('id_postingan', $postingan->id_postingan)->first();
        $existingGameThumbnail = null;
        if (is_array($postingan->content)) {
            foreach ($postingan->content as $c) {
                if (isset($c['type']) && $c['type'] === 'game_thumbnail' && !empty($c['content'])) {
                    $existingGameThumbnail = ltrim($c['content'], '/');
                    break;
                }
            }
        }

        $rawItems = $request->input('items', []);
if (!empty($rawItems) && is_array($rawItems)) {
    foreach ($rawItems as $index => $item) {
        $type = $item['type'] ?? null;

        if (!$type) continue;

        $processed = [
            'type' => $type,
            'content' => null,
        ];

        if ($type === 'image') {
            if ($request->hasFile("items.$index.file")) {
                // Upload gambar baru
                $path = ImageConversionService::storeWebp(
                    $request->file("items.$index.file"),
                    'postingan/images'
                );
                $processed['content'] = $path;
            } elseif (!empty($item['existing_content'])) {
                // ✅ Pakai gambar lama yang sudah ada
                $processed['content'] = $item['existing_content'];
            } else {
                // Tidak ada gambar baru maupun lama → skip
                continue;
            }
        } elseif ($type === 'link') {
            $linkContent = $item['content'] ?? null;
            if (empty($linkContent)) continue; // link kosong → skip
            $processed['content'] = $linkContent;
        }

        $content[] = $processed;
    }
}
        if ($isAllowedGame) {
            if ($request->hasFile('game_thumbnail')) {
            $thumbPath = ImageConversionService::storeWebp($request->file('game_thumbnail'), 'postingan/game_thumbnails');
            $content[] = ['type' => 'game_thumbnail', 'content' => $thumbPath];
            if ($existingGameThumbnail && $existingGameThumbnail !== $thumbPath) {
                Storage::disk('public')->delete($existingGameThumbnail);
            }
        } else {
            // No new upload: if game remains enabled, preserve existing thumbnail; if disabled, remove it
            if ($request->filled('game_enabled')) {
                if ($existingGameThumbnail) {
                    $content[] = ['type' => 'game_thumbnail', 'content' => $existingGameThumbnail];
                }
            } else {
                // game disabled: delete existing game record and thumbnail file
                if ($existingGame) {
                    $existingGame->delete();
                }
                if ($existingGameThumbnail) {
                    Storage::disk('public')->delete($existingGameThumbnail);
                }
            }
        }

        // Create or update Game record if enabled
        if ($request->filled('game_enabled')) {
    $gameName = $request->input('game_name') ?: ($existingGame->game_name ?? 'Matematika');

    // 🔥 Ambil semua game dengan id_postingan sama
    $games = Game::where('id_postingan', $postingan->id_postingan)->get();

    // 🧹 Kalau ada lebih dari 1, hapus sisanya
    if ($games->count() > 1) {
        $games->slice(1)->each(function ($g) {
            $g->delete();
        });
    }

    $game = $games->first();

    if ($game) {
        // 🔥 Kalau nama game berubah → reset score
        if ($game->game_name !== $gameName) {
            $game->update([
                'game_name' => $gameName,
                'score' => 0,
                'playing_time' => 0,
            ]);
        } else {
            // kalau sama, cukup update nama aja (opsional)
            $game->update([
                'game_name' => $gameName,
            ]);
        }
    } else {
        // 🆕 buat baru
        Game::create([
            'id_postingan' => $postingan->id_postingan,
            'id_user' => Auth::id(),
            'game_name' => $gameName,
            'score' => 0,
            'playing_time' => 0,
            'is_active' => true,
        ]);
    }
}

        }

        // Handle game thumbnail and game record logic
        // If a new thumbnail was uploaded, store it and append to content. Remove old thumb file if replaced.
        
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

        $user = auth()->user();
        $query = Postingan::query();

        if ($user->role === 'mahasiswa') {
            $query->where('id_user', $user->id);
        } elseif ($user->role === 'dosen') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('role', 'mahasiswa')
                  ->where('id_angkatan', $user->id_angkatan)
                  ->where('id_jurusan', $user->id_jurusan)
                  ->where('id_keahlian', $user->id_keahlian);
            });
        } elseif ($user->role === 'admin') {
            // Admin can delete all
        }

        $postingan = $query->where('id_postingan', $id)->firstOrFail();

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