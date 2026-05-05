<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageConversionService;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::where('id_mahasiswa', Auth::id())
            ->latest('tanggal_terbit')
            ->get();

        return view('sertifikat.views_sertifikat', compact('sertifikat'));
    }

    public function sertifikat_user()
    {
        $sertifikat = Sertifikat::with('mahasiswa')->latest()->get();
        return view('sertifikat.views_sertifikat_user', compact('sertifikat'));
    }

    public function create()
    {
        return view('sertifikat.views_create_sertifikat');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'lembaga_penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'permanent' => 'nullable|boolean',
            'expired_date' => 'required_without:permanent|nullable|date|after:tanggal_terbit',
            'link_sertifikat' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if ($request->hasFile('link_sertifikat')) {
            $validated['link_sertifikat'] = ImageConversionService::storeWebp($request->file('link_sertifikat'), 'sertifikat');
        }

        $sertifikat = Sertifikat::create([
            'id_mahasiswa' => Auth::id(),
            'nama_sertifikat' => $validated['nama_sertifikat'],
            'lembaga_penerbit' => $validated['lembaga_penerbit'],
            'tanggal_terbit' => $validated['tanggal_terbit'],
            'expired_date' => $request->has('permanent') ? null : $validated['expired_date'],
            'link_sertifikat' => $validated['link_sertifikat'],
        ]);

        // Kirim notifikasi
        $this->sendSertifikatNotifications($sertifikat);

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil ditambahkan!');
    }

    /**
     * Kirim notifikasi terkait sertifikat baru
     */
    private function sendSertifikatNotifications($sertifikat)
    {
        $user = Auth::user();
        $userName = $user->nama_mahasiswa ?? $user->username ?? 'User';
        $sertifikatName = $sertifikat->nama_sertifikat;
        $lembaga = $sertifikat->lembaga_penerbit;
        
        $tanggalTerbit = \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d M Y');
        $tanggalExpired = $sertifikat->expired_date
            ? \Carbon\Carbon::parse($sertifikat->expired_date)->format('d M Y')
            : 'Berlaku permanen';

        // HANYA SATU notifikasi untuk admin
    \App\Http\Controllers\v1\NotificationController::add(
        'certificate-uploaded',
        [
            'title' => 'Sertifikat Baru Diupload',
            'message' => "{$userName} mengupload sertifikat {$sertifikatName} dari {$lembaga}",
            'user_id' => $user->id,
            'user_name' => $userName,
            'sertifikat_id' => $sertifikat->id,
            'sertifikat_name' => $sertifikatName,
            'lembaga_penerbit' => $lembaga,
            'tanggal_terbit' => $tanggalTerbit,
            'expired_date' => $tanggalExpired,
            'link' => url(app()->getLocale() . '/admin/manageSertifikat'),

        ],
        'high'
    );
}

    // EDIT - ambil id dari query parameter
    public function edit(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Sertifikat ID is required');
        }
        
        $sertifikat = Sertifikat::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();

        $this->authorizeEntry($sertifikat);

        return view('sertifikat.views_edit_sertifikat', compact('sertifikat'));
    }

    // UPDATE - ambil id dari query parameter
    public function update(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Sertifikat ID is required');
        }
        
        $sertifikat = Sertifikat::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();
            
        $this->authorizeEntry($sertifikat);

        $validated = $request->validate([
            'nama_sertifikat' => 'required|string|max:255',
            'lembaga_penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'permanent' => 'nullable|boolean',
            'expired_date' => 'required_without:permanent|nullable|date|after:tanggal_terbit',
            'link_sertifikat' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
        unset($validated['permanent']);

        $sertifikat->update($validated);

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    // DESTROY - ambil id dari query parameter
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            abort(404, 'Sertifikat ID is required');
        }
        
        $sertifikat = Sertifikat::where('id', $id)
            ->where('id_mahasiswa', Auth::id())
            ->firstOrFail();
            
        $this->authorizeEntry($sertifikat);

        // Hapus gambar sertifikat dari storage jika ada
        if ($sertifikat->link_sertifikat && Storage::disk('public')->exists($sertifikat->link_sertifikat)) {
            Storage::disk('public')->delete($sertifikat->link_sertifikat);
        }

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