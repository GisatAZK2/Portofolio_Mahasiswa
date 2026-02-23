<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Portofolio;

    
class PortofolioController extends Controller
{
    public function index()
    {
        $portofolios = Portofolio::all();
        return view('views_portofolio', compact('portofolios'));
    }

 
    public function create()
    {
        
        
    }

    public function store(Request $request)
{
    $request->validate([
        'id_mahasiswa' => 'required|exists:users,id',
        'judul' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
        'link_video' => 'nullable|url',
        'link_github' => 'nullable|url',
        'link_project' => 'nullable|url'
    ]);

    if (
        !$request->judul &&
        !$request->deskripsi &&
        !$request->link_video &&
        !$request->link_github &&
        !$request->link_project
    ) {
        return response()->json([
            'status' => 'error',
            'message' => 'Minimal isi salah satu field portfolio'
        ], 422);
    }

    $content = array_filter([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'link_video' => $request->link_video,
        'link_github' => $request->link_github,
        'link_project' => $request->link_project
    ]);

    $portfolio = Portfolio::create([
        'id_mahasiswa' => $request->id_mahasiswa,
        'tanggal' => now(),
        'isi_content' => $content
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Portfolio berhasil ditambahkan',
        'data' => $portfolio
    ], 201);
}

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
    public function update(Request $request, $id)
    {
            $portfolio = Portfolio::find($id);

            if (!$portfolio) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Portfolio tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'judul' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'link_video' => 'nullable|url',
                'link_github' => 'nullable|url',
                'link_project' => 'nullable|url'
            ]);

            // Ambil isi lama (kalau null jadikan array kosong)
            $content = $portfolio->isi_content ?? [];

            // Field yang boleh diupdate / ditambah
            $fields = [
                'judul',
                'deskripsi',
                'link_video',
                'link_github',
                'link_project'
            ];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $content[$field] = $request->$field;
                }
            }

            // Hapus yang null agar bersih
            $content = array_filter($content, function ($value) {
                return !is_null($value);
            });

            $portfolio->update([
                'isi_content' => $content
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Portfolio berhasil diupdate',
                'data' => $portfolio
            ]);
    }



    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            return response()->json([
                'status' => 'error',
                'message' => 'Portfolio tidak ditemukan'
            ], 404);
        }

        $portfolio->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portfolio berhasil dihapus'
        ]);
    }
}
