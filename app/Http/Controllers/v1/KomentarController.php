<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komentar;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_postingan' => 'required|exists:postingan,id_postingan',
                'komentar' => 'required|string|max:1000',
            ]);

            $komentar = Komentar::create([
                'id_user' => Auth::id(),
                'id_postingan' => $request->id_postingan,
                'komentar' => $request->komentar,
                'tanggal' => now(),
            ]);

            // Load user relation
            $komentar->load('user');

            // Check if request wants JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil ditambahkan!',
                    'comment' => $komentar,
                    'user' => $komentar->user,
                ]);
            }

            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar ID is required'
                ], 400);
            }
            abort(404, 'Komentar ID is required');
        }
        
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $komentar
            ]);
        }

        return view('komentar.views_edit_komentar', compact('komentar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar ID is required'
                ], 400);
            }
            abort(404, 'Komentar ID is required');
        }
        
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        $komentar->update([
            'komentar' => $request->komentar,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diperbarui!',
                'comment' => $komentar
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar ID is required'
                ], 400);
            }
            abort(404, 'Komentar ID is required');
        }
        
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $komentar->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}