<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\PortofolioController;
use App\Http\Controllers\v1\ProjekController;
Use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LearningCornerController;

// Halaman yang boleh diakses tanpa login (guest)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', [DashboardController::class, 'search'])->name('search');

// Halaman yang butuh login
Route::middleware('auth')->group(function () {

    Route::get('/profile-page', function () {
        return view('views_profile_page');
    })->name('profile-page');

    // CRUD Project – hanya user yang sudah login
 Route::middleware('auth')->group(function () {
    Route::resource('project', ProjekController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    Route::resource('learning-corner', LearningCornerController::class)
        ->names([
            'index'   => 'learning-corner.index',
            'create'  => 'learning-corner.create',
            'store'   => 'learning-corner.store',
            'edit'    => 'learning-corner.edit',
            'update'  => 'learning-corner.update',
            'destroy' => 'learning-corner.destroy',
        ]);
});


    // CRUD Portfolio – hanya user yang sudah login
    Route::prefix('portofolio')->name('portofolio.')->group(function () {         // boleh guest juga (lihat daftar)
        Route::get('/create', [PortofolioController::class, 'create'])->name('create');
        Route::post('/', [PortofolioController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PortofolioController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PortofolioController::class, 'update'])->name('update');
        Route::delete('/{id}', [PortofolioController::class, 'destroy'])->name('destroy');
    });

    // Tambahan: route profile user (contoh)
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

// Route auth
Route::get('/register', [UserController::class, 'showRegister'])->name('register');

Route::get('/Portofolio', [PortofolioController::class, 'index'])->name('portofolio.index');

Route::post('/register', [UserController::class, 'register']);

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');