<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class AdminController extends Controller
{
    public function mahasiswa()
{
    $mahasiswa = User::where('role', 'mahasiswa')
        ->withCount(['projects','sertifikats','learning_corners'])
        ->latest()
        ->get();

    return view('admin.daftar-mahasiswa', compact('mahasiswa'));
}
}
