<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\PortofolioController;
use App\Http\Controllers\v1\ProjekController;

Route::prefix('v1')->group(function () {
    Route::get('/portofolio', [PortofolioController::class, 'index']);
    Route::post('/portofolio', [PortofolioController::class, 'store']);
    Route::get('/portofolio/{id}', [PortofolioController::class, 'show']);
    Route::put('/portofolio/{id}', [PortofolioController::class, 'update']);
    Route::delete('/portofolio/{id}', [PortofolioController::class, 'destroy']);
});

Route::prefix('v1')->group(function () {
    Route::get('/project', [ProjekController::class, 'index']);
    Route::post('/project', [ProjekController::class, 'store']);
    Route::get('/project/{id}', [ProjekController::class, 'show']);
    Route::put('/project/{id}', [ProjekController::class, 'update']);
    Route::delete('/project/{id}', [ProjekController::class, 'destroy']);
});