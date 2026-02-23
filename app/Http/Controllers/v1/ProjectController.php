<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    // GET ALL
    public function index()
    {
        $data = Project::with('mahasiswa')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // GET BY ID
    public function show($id)
    {
        $project = Project::with('mahasiswa')->find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $project
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date',
            'link_project' => 'nullable|string',
            'id_mahasiswa' => 'required|exists:users,id'
        ]);

        $project = Project::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil ditambahkan',
            'data' => $project
        ], 201);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama_project' => 'sometimes|string|max:255',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_akhir' => 'nullable|date',
            'link_project' => 'nullable|string',
            'id_mahasiswa' => 'sometimes|exists:users,id'
        ]);

        $project->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil diupdate',
            'data' => $project
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project tidak ditemukan'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil dihapus'
        ]);
    }
}