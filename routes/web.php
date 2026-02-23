<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\PortofolioController;

Route::get('/', function () {
    return view('views_dashboard');
});

Route::get('/hasil-search', function () {
    return view('views_result_search');
})->name('hasil-search');

Route::get('/profile-page', function () {
    return view('profile_page');
})->name('profile-page');


Route::get('/portofolio', [PortofolioController::class, 'index'])->name('portofolio.index');
      
