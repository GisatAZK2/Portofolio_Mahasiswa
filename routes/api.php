<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\PortofolioController;

Route::prefix('v1')->group(function () {
    Route::get('/portofolio', [PortofolioController::class, 'index']);
    Route::post('/portofolio', [PortofolioController::class, 'store']);
    Route::get('/portofolio/{id}', [PortofolioController::class, 'show']);
    Route::put('/portofolio/{id}', [PortofolioController::class, 'update']);
    Route::delete('/portofolio/{id}', [PortofolioController::class, 'destroy']);
});