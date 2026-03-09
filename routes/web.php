<?php

use App\Http\Controllers\v1\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\UserController;
//use App\Http\Controllers\v1\PortofolioController;
use App\Http\Controllers\v1\ProjekController;
Use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LearningCornerController;
use App\Http\Controllers\v1\SertifikatController;

// Halaman Admin
Route::get('/admin/dashboard', function() {
    return view('admin.dashboard');
});

// Halaman guest
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', [DashboardController::class, 'search'])->name('search');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {

    Route::get('/MyDashboard', [DashboardController::class, 'myDashboard'])->name('dashboard.me');

    Route::get('/profile-page', function () {
        return view('views_profile_page');
    })->name('profile-page');

    // CRUD Project
    Route::resource('project', ProjekController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    //CRUD Sertifikat
    Route::resource('sertifikat', SertifikatController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
        ]);

    // CRUD Learning Corner
    Route::get('/project/{project}/learning-corner/create',
        [LearningCornerController::class, 'create']
        )->name('learning-corner.create');
    Route::post('/project/{project}/learning-corner',
        [LearningCornerController::class, 'store']
        )->name('learning-corner.store');
    
    Route::post('/learning-corner-mass/mass-destroy',
    [LearningCornerController::class, 'massDestroy']
    )->name('learning-corner.mass-destroy');

    Route::get('/learning-corner/{learningCorner}/edit',
        [LearningCornerController::class, 'edit']
        )->name('learning-corner.edit');

    Route::put('/learning-corner/{learningCorner}',
        [LearningCornerController::class, 'update']
        )->name('learning-corner.update');


    Route::delete('/learning-corner/{learning_corner}', 
    [LearningCornerController::class, 'destroy']
)->name('learning-corner.destroy');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Auth routes (bisa di luar middleware auth)

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Route ini sepertinya boleh guest juga
//Route::get('/portfolio/{user}', [PortofolioController::class, 'show'])->name('portfolio.show');
route::get('/ProjectUser', [ProjekController::class, 'project_user'])->name('project.project_user');
Route::get('/project/{id}', [ProjekController::class, 'show'])->name('project.show');
Route::get('/portfolio/{user}', [DashboardController::class, 'show'])->name('portfolio.show');
Route::get('/settings', function () {
    return view('settings');
})->name('settings');


//Khusus dosen yang bisa akses
Route::middleware(['auth','dosen'])->prefix('admin')->group(function () {
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('admin.mahasiswa');

    Route::get('/project', function () {
        return view('admin.project');
    })->name('admin.project');

    Route::get('/learning', function () {
        return view('admin.learning-corner');
    })->name('admin.learning');

    Route::get('/sertifikat', function () {
        return view('admin.sertifikat');
    })->name('admin.sertifikat');

});

Route::middleware('auth', 'dosen')->group(function () {
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
});

Route::post('/toggle-sidebar', function (Request $request) {
    Session::put('sidebar_collapsed', $request->collapsed);
    return response()->json(['success' => true]);
})->middleware('web');


Route::get('/learning-corner-mahasiswa', [LearningCornerController::class, 'learning_corner_user'])->name('learning-corner-mahasiswa');
Route::get('/sertifikat-mahasiswa', [SertifikatController::class, 'sertifikat_user'])->name('sertifikat-mahasiswa');

