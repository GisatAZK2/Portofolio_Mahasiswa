<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Portofolio;
use App\Models\User;
use App\Models\LearningCorner;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
       
        $totalMahasiswa = User::count();
        $totalPortofolio = Portofolio::count();
        $totalLearning = LearningCorner::count();
        $totalProject = Project::count();

        // =========================
        // AMBIL POSTING RANDOM
        // =========================

        $randomPortofolio = Portofolio::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'portofolio';
                return $item;
            });

        $randomLearning = LearningCorner::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'learning';
                return $item;
            });

        $randomProject = Project::with('mahasiswa')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->type = 'project';
                return $item;
            });

        // Gabungkan & acak lagi
        $randomPosts = $randomPortofolio
            ->concat($randomLearning)
            ->concat($randomProject)
            ->shuffle()
            ->take(6);

        return view('views_dashboard', compact(
            'totalMahasiswa',
            'totalPortofolio',
            'totalLearning',
            'totalProject',
            'randomPosts'
        ));
    }

}
