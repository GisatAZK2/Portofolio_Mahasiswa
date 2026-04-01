<?php

use App\Http\Controllers\v1\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\UserController;
//use App\Http\Controllers\v1\PortofolioController;
use App\Http\Controllers\v1\ProjekController;
use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LearningCornerController;
use App\Http\Controllers\v1\SertifikatController;
use App\Http\Controllers\v1\DosenController;


// Halaman guest
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', [DashboardController::class, 'search'])->name('search');
Route::get('/pagination-fragment', [DashboardController::class, 'paginationFragment'])->name('pagination.fragment');

// Semua route yang butuh login
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    Route::get('/MyDashboard', [DashboardController::class, 'myDashboard'])->name('dashboard.me');

    Route::prefix('keahlian-tambahan')->name('keahlian-tambahan.')->group(function () {
        Route::get('/', [UserController::class, 'keahliantambahanlist'])->name('index');
        Route::post('/', [UserController::class, 'storeKeahlianTambahan'])->name('store');
        Route::delete('/{id}', [UserController::class, 'destroyKeahlianTambahan'])->name('destroy');
    });

    Route::get('/profile-page', function () {
        return view('views_profile_page');
    })->name('profile-page');

    // CRUD Project
    Route::resource('project', ProjekController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    //CRUD Sertifikat
    Route::resource('sertifikat', SertifikatController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    // CRUD Learning Corner
    Route::get(
        '/project/{project}/learning-corner/create',
        [LearningCornerController::class, 'create']
    )->name('learning-corner.create');
    Route::post(
        '/project/{project}/learning-corner',
        [LearningCornerController::class, 'store']
    )->name('learning-corner.store');


    Route::get(
        '/learning-corner/{learningCorner}/edit',
        [LearningCornerController::class, 'edit']
    )->name('learning-corner.edit');

    Route::put(
        '/learning-corner/{learningCorner}',
        [LearningCornerController::class, 'update']
    )->name('learning-corner.update');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {

    Route::post(
        '/project/{project}/tasks',
        [ProjekController::class, 'storeTask']
    )->name('project.tasks.store');
    Route::patch(
        '/project/{project}/tasks/{task}',
        [ProjekController::class, 'updateTask']
    )->name('project.tasks.update');
    Route::patch(
        '/project/{project}/tasks/{task}/complete',
        [ProjekController::class, 'completeTask']
    )->name('project.tasks.complete');
    Route::delete(
        '/project/{project}/tasks/{task}',
        [ProjekController::class, 'destroyTask']
    )->name('project.tasks.destroy');

    Route::post(
        '/learning-corner-mass/mass-destroy',
        [LearningCornerController::class, 'massDestroy']
    )->name('learning-corner.mass-destroy');


    Route::delete('/learning-corner/{learning_corner}', [LearningCornerController::class, 'destroy'])
        ->name('learning-corner.destroy')
        ->middleware('role:admin,dosen,mahasiswa');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('index');

    Route::prefix('manageUser')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'ListUser'])->name('index');
        Route::get('/Details/{user}', [AdminController::class, 'DetailsUser'])->name('details');
        Route::patch('/edit/{user}', [AdminController::class, 'UpdateUser'])->name('edit');
        Route::get('/AddUser', [AdminController::class, 'ViewAddUser'])->name('ViewCreate');
        Route::post('/StoreUser', [AdminController::class, 'AddUser'])->name('StoreUser');
        Route::delete('/DeleteUser/{user}', [AdminController::class, 'destroyUser'])->name('destroy');
        Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyUsers'])->name('bulkDestroy');
        Route::patch('/{user}/update-status', [UserController::class, 'updateStatus'])->name('update-status');
    });

    Route::prefix('manageSertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', [AdminController::class, 'sertifikat'])->name('index');
        Route::get('/AddSertifikat', [AdminController::class, 'TambahSertifikat'])->name('create');
        Route::get('/Details/{sertifikat}', [AdminController::class, 'DetailsSertifikat'])->name('details');
        Route::patch('/edit/{sertifikat}', [AdminController::class, 'UpdateSertifikat'])->name('update');
        Route::post('/store', [AdminController::class, 'StoreSertifikat'])->name('store');
        Route::delete('/DeleteSertifikat/{sertifikat}', [AdminController::class, 'DestroySertifikat'])->name('destroy');
        Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    Route::patch('/sertifikat/{id}/approve', [AdminController::class, 'approve'])->name('sertifikat.approve');
    Route::patch('/sertifikat/{id}/reject', [AdminController::class, 'reject'])->name('sertifikat.reject');

    Route::prefix('manageAngkatan')->name('angkatan.')->group(function () {
        Route::get('/', [AdminController::class, 'ListAngkatan'])->name('index');
        Route::get('/AddAngkatan', [AdminController::class, 'TambahAngkatan'])->name('create');
        Route::get('/Details/{angkatan}', [AdminController::class, 'DetailsAngkatan'])->name('details');
        Route::patch('/edit/{angkatan}', [AdminController::class, 'UpdateAngkatan'])->name('update');
        Route::post('/store', [AdminController::class, 'StoreAngkatan'])->name('store');
        Route::delete('/DeleteAngkatan/{angkatan}', [AdminController::class, 'DestroyAngkatan'])->name('destroy');
        Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyAngkatan'])->name('bulk-destroy');
    });

    Route::prefix('manageProject')->name('projects.')->group(function () {
        Route::get('/', [AdminController::class, 'projects'])->name('index');
        Route::get('/create', [AdminController::class, 'TambahProjects'])->name('create');
        Route::post('/store', [AdminController::class, 'StoreProject'])->name('store');
        Route::get('/Details/{project}', [AdminController::class, 'EditProjects'])->name('details');
        Route::post('/EditProject/{project}', [AdminController::class, 'UpdateProject'])->name('update');
        Route::delete('/DeleteProject/{project}', [AdminController::class, 'DestroyProject'])->name('delete');
        Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyProject'])->name('bulk-delete');
    });

});


Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'index'])->name('dashboard');

    Route::prefix('manageUser')->name('users.')->group(function () {
        Route::get('/', [DosenController::class, 'ListUser'])->name('index');
        Route::get('/Details/{user}', [DosenController::class, 'DetailsUser'])->name('details');
        Route::patch('/edit/{user}', [DosenController::class, 'UpdateUser'])->name('edit');
        Route::get('/AddUser', [DosenController::class, 'ViewAddUser'])->name('ViewCreate');
        Route::post('/StoreUser', [DosenController::class, 'AddUser'])->name('StoreUser');
        Route::delete('/DeleteUser/{user}', [DosenController::class, 'destroyUser'])->name('destroy');
        Route::patch('/{user}/update-status', [UserController::class, 'updateStatus'])->name('update-status');
    });

    Route::prefix('manageSertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', [DosenController::class, 'sertifikat'])->name('index');
        Route::get('/AddSertifikat', [DosenController::class, 'TambahSertifikat'])->name('create');
        Route::get('/Details/{sertifikat}', [DosenController::class, 'DetailsSertifikat'])->name('details');
        Route::patch('/edit/{sertifikat}', [DosenController::class, 'UpdateSertifikat'])->name('update');
        Route::post('/store', [DosenController::class, 'StoreSertifikat'])->name('store');
        Route::delete('/DeleteSertifikat/{sertifikat}', [DosenController::class, 'DestroySertifikat'])->name('destroy');
        Route::delete('/bulk-destroy', [DosenController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    Route::patch('/sertifikat/{id}/approve', [DosenController::class, 'approve'])->name('sertifikat.approve');
    Route::patch('/sertifikat/{id}/reject', [DosenController::class, 'reject'])->name('sertifikat.reject');

    Route::prefix('manageProject')->name('projects.')->group(function () {
        Route::get('/', [DosenController::class, 'projects'])->name('index');
        Route::get('/create', [DosenController::class, 'TambahProjects'])->name('create');
        Route::post('/store', [DosenController::class, 'StoreProject'])->name('store');
        Route::get('/Details/{project}', [DosenController::class, 'EditProjects'])->name('details');
        Route::patch('/EditProject/{project}', [DosenController::class, 'UpdateProject'])->name('update');
        Route::delete('/DeleteProject/{project}', [DosenController::class, 'DestroyProject'])->name('delete');
        Route::delete('/bulk-destroy', [DosenController::class, 'bulkDestroyProject'])->name('bulk-delete');
    });
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

// Auth routes (bisa di luar middleware auth)
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);


Route::get('/learning-corner-mahasiswa', [LearningCornerController::class, 'learning_corner_user'])->name('learning-corner-mahasiswa');
Route::get('/sertifikat-mahasiswa', [SertifikatController::class, 'sertifikat_user'])->name('sertifikat-mahasiswa');



