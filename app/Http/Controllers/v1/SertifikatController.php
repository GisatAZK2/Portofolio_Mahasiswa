<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::where('id_mahasiswa', Auth::id())
            ->latest('tanggal_terbit')
            ->get();

        return view('sertifikat.views_sertifikat', compact('sertifikat'));
    }

    public function create()
    {
        return view('sertifikat.views_create_sertifikat');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_sertifikat'   => 'required|string|max:255',
        'lembaga_penerbit'  => 'required|string|max:255',
        'tanggal_terbit'    => 'required|date',
        'link_sertifikat'   => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
    ]);
    
    if ($request->hasFile('link_sertifikat')) {
        $path = $request->file('link_sertifikat')
                        ->store('sertifikat', 'public');
        $validated['link_sertifikat'] = $path;
    }

    Sertifikat::create([
        'id_mahasiswa'     => Auth::id(),
        'nama_sertifikat'  => $validated['nama_sertifikat'],
        'lembaga_penerbit' => $validated['lembaga_penerbit'],
        'tanggal_terbit'   => $validated['tanggal_terbit'],
        'link_sertifikat'  => $validated['link_sertifikat'],
    ]);

    return redirect()->route('sertifikat.index')
        ->with('success', 'Sertifikat berhasil ditambahkan!');
}
    public function edit(Sertifikat $sertifikat)
    {
        $this->authorizeEntry($sertifikat);

        $sertifikat = Sertifikat::where('id', $sertifikat->id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();


        return view('sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }
   
public function update(Request $request, Sertifikat $sertifikat)
{
    $this->authorizeEntry($sertifikat);

    $validated = $request->validate([
        'nama_sertifikat'   => 'required|string|max:255',
        'lembaga_penerbit'  => 'required|string|max:255',
        'tanggal_terbit'    => 'required|date',
        'link_sertifikat'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
    ]);

    if ($request->hasFile('link_sertifikat')) {

       
        if ($sertifikat->link_sertifikat && 
            Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
            
            Storage::disk('public')->delete($sertifikat->link_sertifikat);
        }

        $path = $request->file('link_sertifikat')
                        ->store('sertifikat', 'public');

        $validated['link_sertifikat'] = $path;
    } else {
       
        $validated['link_sertifikat'] = $sertifikat->link_sertifikat;
    }

    $sertifikat->update($validated);

    return redirect()->route('sertifikat.index')
        ->with('success', 'Sertifikat berhasil diperbarui!');
}

    public function destroy(Sertifikat $sertifikat)
    {
        $this->authorizeEntry($sertifikat);

        $sertifikat->delete();

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    private function authorizeEntry(Sertifikat $entry): void
    {
        if ($entry->id_mahasiswa !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }
    }
}
