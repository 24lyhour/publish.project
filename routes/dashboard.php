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
     * Product Management Routes - Outside dashboard prefix to have clean names
     */
    Route::prefix('dashboard/management/products')->group(function () {
        Route::resource('/', ProductController::class)
            ->parameters(['' => 'product'])
            ->names([
                'index' => 'products.index',
                'create' => 'products.create',
                'store' => 'products.store',
                'show' => 'products.show',
                'edit' => 'products.edit',
                'update' => 'products.update',
                'destroy' => 'products.destroy'
            ]);
        
        // Product delete route
        Route::get('/{product}/delete', [ProductController::class, 'delete'])
            ->name('products.delete');
    });
    
    /**
     * Dashboard Management Routes
     * 
     * All management routes are prefixed with 'dashboard' for better organization
     * and grouped under auth middleware for security
     * 
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     * @see DashboardController
     * @see CategoryController
     * @see RestaurantController
     * @see MenuController
     * 
     * @link App\Http\Controllers\DashboardController
     * @link App\Http\Controllers\CategoryController
     * @link App\Http\Controllers\RestaurantController
     * @link App\Http\Controllers\MenuController
     */
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        
        /**
         * Management Group Routes
         * Grouped routes for better organization and middleware management
         */
        Route::group(['prefix' => 'management'], function () {
            
            /**
             * Category Management Group
             */
            Route::group(['prefix' => 'categories'], function () {
                
                
                 // Category delete confirmation route
                Route::get('category/create', [CategoryController::class, 'create'])
                ->name('categories.create');
                Route::get('category/edit', [CategoryController::class, 'edit'])
                ->name('categories.edit');
                Route::get('/{category}/delete', [CategoryController::class, 'delete'])
                    ->name('categories.delete');

                    
                Route::resource('/', CategoryController::class)->parameters(['' => 'category'])->names([
                    'index' => 'categories.index',
                    'create' => 'categories.create',
                    'store' => 'categories.store', 
                    'show' => 'categories.show',
                    'edit' => 'categories.edit',
                    'update' => 'categories.update',
                    'destroy' => 'categories.destroy'
                ]);
                
            });
            
            /**
             * Restaurant Management Group
             */
            Route::group(['prefix' => 'restaurants'], function () {
                Route::resource('/', RestaurantController::class)->parameters(['' => 'restaurant'])->names([
                    'index' => 'restaurants.index',
                    'create' => 'restaurants.create',
                    'store' => 'restaurants.store',
                    'show' => 'restaurants.show', 
                    'edit' => 'restaurants.edit',
                    'update' => 'restaurants.update',
                    'destroy' => 'restaurants.destroy'
                ]);
            });
            
            /**
             * Menu Management Group
             */
            Route::group(['prefix' => 'menus'], function () {
                Route::resource('/', MenuController::class)->parameters(['' => 'menu'])->names([
                    'index' => 'menus.index',
                    'create' => 'menus.create',
                    'store' => 'menus.store',
                    'show' => 'menus.show',
                    'edit' => 'menus.edit', 
                    'update' => 'menus.update',
                    'destroy' => 'menus.destroy'
                ]);
            });
            
        }); // End management group
        
    }); // End dashboard prefix group
    
});