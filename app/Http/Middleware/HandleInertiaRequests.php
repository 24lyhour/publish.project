<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Closure;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     * 
     * @param Request $request
     * 
     * @return string|null
     * 
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'menu' => fn () => app(MenuService::class)->getMenu(),
        ]);
    }

    /**
     * Handle an incoming request.
     * 
     * @param Request $request
     * @param Closure $next
     * 
     * @return void
     * 
     * 
     */
    public function handle(Request $request, Closure $next)
    {

        app(MenuService::class)->addMenuItem(
            'main',
            'dashboard',
            'Dashboard',
            'dashboard',
            'mdi-view-dashboard',
            1,
            'VIEW_DASHBOARD'
        );

        app(MenuService::class)->addMenuItem(
            'main',
            'products',
            'Products',
            'products.index',
            'mdi-package-variant-closed',
            2,
            'VIEW_PRODUCTS',
            null,
            ['style' => ['color' => 'black', 'marginTop' => '10px']]
        );

        // // Example: Add submenu under Products if needed
        // app(MenuService::class)->addSubmenuItem('main', 'Products', 'Product List', route('products.index'), 'mdi-package', 1, 'VIEW_PRODUCTS');

        return parent::handle($request, $next);
    }
}