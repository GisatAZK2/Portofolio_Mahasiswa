<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\PortofolioController;
use App\Http\Controllers\v1\ProjekController;
Use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LearningCornerController;

// Halaman guest
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', [DashboardController::class, 'search'])->name('search');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {

    Route::get('/profile-page', function () {
        return view('views_profile_page');
    })->name('profile-page');

    // CRUD Project
    Route::resource('project', ProjekController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    // CRUD Learning Corner
    Route::resource('learning-corner', LearningCornerController::class)
        ->names([
            'index'   => 'learning-corner.index',
            'create'  => 'learning-corner.create',
            'store'   => 'learning-corner.store',
            'edit'    => 'learning-corner.edit',
            'update'  => 'learning-corner.update',
            'destroy' => 'learning-corner.destroy',
        ]);

    // CRUD Portofolio (manual, bukan resource)
    Route::prefix('portofolio')->name('portofolio.')->group(function () {
        Route::get('/create', [PortofolioController::class, 'create'])->name('create');
        Route::post('/', [PortofolioController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PortofolioController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PortofolioController::class, 'update'])->name('update');
        Route::delete('/{id}', [PortofolioController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

// Auth routes (bisa di luar middleware auth)
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Route ini sepertinya boleh guest juga
Route::get('/Portofolio', [PortofolioController::class, 'index'])->name('portofolio.index');

Route::get('/PortofolioUser', [PortofolioController::class, 'indexuser'])->name('portofolio.indexuser');
Route::get('/portfolio/{user}', [PortofolioController::class, 'show'])->name('portfolio.show');

Route::get('/projectuser', [ProjekController::class, 'indexuser'])->name('project.indexuser');
