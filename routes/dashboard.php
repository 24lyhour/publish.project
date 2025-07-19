<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;

/**
 * Dashboard Routes with Authentication
 * 
 * All dashboard management routes are grouped under auth middleware
 * for secure access to admin functionality
 */
Route::middleware(['auth'])->group(function () {
    
    /**
     * Main Dashboard
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /**
     * Dashboard Management Routes
     * 
     * All management routes are prefixed with 'dashboard' for better organization
     * and grouped under auth middleware for security
     */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        
        /**
         * Product Management
         */
        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create', 
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy'
        ]);
        
        // Product modal routes
        Route::get('/products/{product}/delete', [ProductController::class, 'confirmDelete'])
            ->name('products.confirm-delete');
        Route::get('/products/modal/create', [ProductController::class, 'createModal'])
            ->name('products.modal.create');
        Route::get('/products/{product}/modal/edit', [ProductController::class, 'editModal'])
            ->name('products.modal.edit');
        
        /**
         * Category Management
         */
        Route::resource('categories', CategoryController::class)->names([
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store', 
            'show' => 'categories.show',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy'
        ]);
        
        /**
         * Restaurant Management
         */
        Route::resource('restaurants', RestaurantController::class)->names([
            'index' => 'restaurants.index',
            'create' => 'restaurants.create',
            'store' => 'restaurants.store',
            'show' => 'restaurants.show', 
            'edit' => 'restaurants.edit',
            'update' => 'restaurants.update',
            'destroy' => 'restaurants.destroy'
        ]);
        
        /**
         * Menu Management
         */
        Route::resource('menus', MenuController::class)->names([
            'index' => 'menus.index',
            'create' => 'menus.create',
            'store' => 'menus.store',
            'show' => 'menus.show',
            'edit' => 'menus.edit', 
            'update' => 'menus.update',
            'destroy' => 'menus.destroy'
        ]);
        
    });
    
});