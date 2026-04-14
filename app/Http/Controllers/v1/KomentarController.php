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
        $request->validate([
            'id_postingan' => 'required|exists:postingan,id_postingan',
            'komentar' => 'required|string|max:1000',
        ]);

        Komentar::create([
            'id_user' => Auth::id(),
            'id_postingan' => $request->id_postingan,
            'komentar' => $request->komentar,
            'tanggal' => now(),
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        $komentar->update([
            'komentar' => $request->komentar,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $komentar->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}
