<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * @return void
     * 
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\MenuService::class, function ($app) {
            return new \App\Services\MenuService();
        });
    }
    

    /**
     * Bootstrap any application services.
     * 
     * @return void
     * 
     * 
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
