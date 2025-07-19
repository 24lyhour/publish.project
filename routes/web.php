<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

    /**
     * Default Route Dashboard
     * 
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     */
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    /**
     * Middleware Author
     * 
     */
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Product routes
        Route::resource('products', ProductController::class);
        Route::get('/products/{product}/delete', [ProductController::class, 'confirmDelete'])->name('products.confirm-delete');
    });

    /**
     * Require route
     * 
     */
    require __DIR__.'/auth.php';
    require __DIR__.'/dashboard.php';