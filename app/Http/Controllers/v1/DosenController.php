<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DosenController extends Controller
{
    public function index() {
        return view('dosen.index');
    }

    public function mahasiswa(Request $request)
    {
        $currentUser = auth()->user();
        
        // Ambil mahasiswa yang sesuai dengan jurusan, angkatan, dan keahlian dosen
        $mahasiswa = User::where('role', 'mahasiswa')
            ->where('id_jurusan', $currentUser->id_jurusan)
            ->where('id_angkatan', $currentUser->id_angkatan)
            ->where('id_keahlian', $currentUser->id_keahlian)
            ->withCount(['projects', 'sertifikats', 'learning_corners'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.daftar-mahasiswa', compact('mahasiswa'));
    }

    public function projects(){
        return view('dosen.project');
    }

    public function sertifikat(){
        return view('dosen.sertifikat');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
