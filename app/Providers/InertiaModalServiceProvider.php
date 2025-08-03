<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory;
use Inertia\Response;

class InertiaModalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ResponseFactory::macro('modal', function ($component, $props = []) {
            $response = ResponseFactory::render($component, array_merge($props, [
                'isModal' => true,
            ]));
            
            // Add baseRoute method to Response
            $response->macro('baseRoute', function ($route) use ($response) {
                return $response->with('baseRoute', $route);
            });
            
            return $response;
        });
        
        // Add baseRoute macro to Response class
        Response::macro('baseRoute', function ($route) {
            return $this->with('baseRoute', $route);
        });
    }
}