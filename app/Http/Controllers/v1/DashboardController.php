<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Portofolio;
use App\Models\User;
use App\Models\LeraningCorner;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
{
    $data = User::with(['projects', 'portofolio', 'learning_corners'])->get();

    return view('dashboard.index', compact('data'));
}
}
