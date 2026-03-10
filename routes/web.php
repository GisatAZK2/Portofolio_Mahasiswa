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
use App\Http\Controllers\v1\DosenController;


// Halaman guest
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', [DashboardController::class, 'search'])->name('search');

// Semua route yang butuh login
Route::middleware(['auth','role:mahasiswa'])->group(function () {

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


    Route::delete('/learning-corner/{learning_corner}', [LearningCornerController::class, 'destroy'])->name('learning-corner.destroy');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/admin/manageUser', [AdminController::class, 'ListUser'])->name('admin.users.index');
    Route::get('/admin/manageUser/Details/{user}',[AdminController::class, 'DetailsUser'])->name('admin.users.details');
    Route::patch('/admin/manageUser/edit/{user}',[AdminController::class, 'UpdateUser'])->name('admin.users.edit');
    Route::get('/admin/AddUser', [AdminController::class, 'ViewAddUser'])->name('admin.users.ViewCreate');
    Route::post('/admin/StoreUser', [AdminController::class, 'AddUser'])->name('admin.users.StoreUser');
    Route::delete('/admin/DeleteUser/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/manageProject', [AdminController::class, 'projects'])->name('admin.projects.index');
    Route::get('/admin/manageSertfikat', [AdminController::class, 'sertifikat'])->name('admin.sertifikat.index');
});

Route::middleware(['auth','role:dosen'])->group(function () {
     Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dashboard.dosen');
     Route::get('/dosen/manageUser', [DosenController::class, 'mahasiswa'])->name('dosen.mahasiswa.index');
    Route::get('/dosen/manageProject', [DosenController::class, 'projects'])->name('dosen.projects.index');
    Route::get('/dosen/manageSertfikat', [DosenController::class, 'sertifikat'])->name('dosen.sertifikat.index');

});


Route::middleware(['auth'])->group(function () {
    Route::patch('/user/{id}/update-status', [App\Http\Controllers\v1\UserController::class, 'updateStatusPengajuan'])
        ->name('user.update-status')
        ->middleware('role:admin,dosen');
});


Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


route::get('/ProjectUser', [ProjekController::class, 'project_user'])->name('project.project_user');
Route::get('/project/{id}', [ProjekController::class, 'show'])->name('project.show');
Route::get('/portfolio/{user}', [DashboardController::class, 'show'])->name('portfolio.show');



Route::get('/pengajuan-akun', [UserController::class, 'showRegister'])->name('pengajuan-akun');
Route::post('/pengajuan-akun', [UserController::class, 'register'])->name('register');


Route::post('/toggle-sidebar', function (Request $request) {
Session::put('sidebar_collapsed', $request->collapsed);
    return response()->json(['success' => true]);
})->middleware('web');


Route::get('/learning-corner-mahasiswa', [LearningCornerController::class, 'learning_corner_user'])->name('learning-corner-mahasiswa');
Route::get('/sertifikat-mahasiswa', [SertifikatController::class, 'sertifikat_user'])->name('sertifikat-mahasiswa');



