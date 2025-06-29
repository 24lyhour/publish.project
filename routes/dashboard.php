<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

    /**
     * Route get dashboard Controller
     * 
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     * 
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');