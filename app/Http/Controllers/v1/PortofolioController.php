<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PortofolioController extends Controller
{
    public function index()
    {
        
        $data = User::with(['projects', 'portofolio', 'learning_corners'])->get();
        return view('portofolio.views_portofolio', compact('data'));
       
    }


    public function create()
    {
        $mahasiswa = User::orderBy('nama_mahasiswa')->get(['id', 'nama_mahasiswa']);
        return view('portofolio.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'nullable|string|max:255',
            'deskripsi'      => 'nullable|string',
            'link_project'   => 'nullable|url|max:500',
            'link_github'    => 'nullable|url|max:500',
            'link_video'     => 'nullable|url|max:500',
        ]);

        $content = array_filter($request->only([
            'judul', 'deskripsi', 'link_project', 'link_github', 'link_video'
        ]), fn($value) => !is_null($value) && $value !== '');

        if (empty($content)) {
            return back()->withInput()->withErrors(['portfolio' => 'Minimal isi salah satu field (judul, deskripsi, atau link)']);
        }

        Portofolio::create([
            'id_mahasiswa' => Auth::id(), //mengambil id_mahasiswa lewat Auth
            'tanggal'      => now(),
            'isi_content'  => $content,
        ]);

        return redirect()->route('portofolio.index')
            ->with('success', 'Portofolio berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $portfolio = Portofolio::with('mahasiswa')->findOrFail($id);
        $mahasiswa = User::orderBy('nama_mahasiswa')->get(['id', 'nama_mahasiswa']);

        return view('portofolio.edit', compact('portfolio', 'mahasiswa'));
    }

   public function update(Request $request, $id)
{
    $portfolio = Portofolio::findOrFail($id);

    $validated = $request->validate([
        'judul'          => 'nullable|string|max:255',
        'deskripsi'      => 'nullable|string',
        'link_project'   => 'nullable|url|max:500',
        'link_github'    => 'nullable|url|max:500',
        'link_video'     => 'nullable|url|max:500',
        'remove_links'   => 'array'
    ]);

    $content = $portfolio->isi_content ?? [];

    // Ambil link yang ingin dihapus
    $removeLinks = $request->remove_links ?? [];

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Hapus LINK yang dicentang
    |--------------------------------------------------------------------------
    */
    foreach ($removeLinks as $linkField) {
        if (in_array($linkField, ['link_project', 'link_github', 'link_video'])) {
            unset($content[$linkField]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Update judul & deskripsi
    |--------------------------------------------------------------------------
    */
    if ($request->filled('judul')) {
        $content['judul'] = $request->judul;
    }

    if ($request->filled('deskripsi')) {
        $content['deskripsi'] = $request->deskripsi;
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ Update link HANYA jika tidak dicentang untuk dihapus
    |--------------------------------------------------------------------------
    */
    foreach (['link_project', 'link_github', 'link_video'] as $link) {

        // Kalau link dicentang untuk dihapus → skip
        if (in_array($link, $removeLinks)) {
            continue;
        }

        // Kalau ada value baru → update
        if ($request->filled($link)) {
            $content[$link] = $request->$link;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ Minimal harus ada judul atau deskripsi
    |--------------------------------------------------------------------------
    */
    if (empty($content['judul']) && empty($content['deskripsi'])) {
        return back()->withInput()->withErrors([
            'portfolio' => 'Judul atau Deskripsi minimal harus ada.'
        ]);
    }

    $portfolio->update([
        'isi_content' => $content,
        'tanggal'     => now(),
    ]);

    return redirect()->route('portofolio.index')
        ->with('success', 'Portofolio berhasil diperbarui!');
}
    public function destroy($id)
    {
        $portfolio = Portofolio::findOrFail($id);
        $portfolio->delete();

        return redirect()->route('portofolio.index')
            ->with('success', 'Portofolio berhasil dihapus!');
    }
}