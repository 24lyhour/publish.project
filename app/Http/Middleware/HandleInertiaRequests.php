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
        $menuService = app(MenuService::class);

        // Dashboard item
        $menuService->addMenuItem(
            menu: 'primary',
            id: 'dashboard',
            title: 'Dashboard',
            url: 'dashboard',
            icon: 'mdi-view-dashboard',
            order: 10,
            permissions: 'VIEW_DASHBOARD',
            route: 'dashboard'
        );

        // Menu group with submenus
        $menuService->addMenuItem(
            menu: 'primary',
            id: 'menu',
            title: 'Menu',
            url: '#', // No direct URL since it has submenus
            icon: 'mdi-menu',
            order: 20,
            permissions: 'MANAGE_MENU',
            route: null, // No route for parent menu
            hasSubmenu: true
        );

        // Add Products submenu
        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'menu',
            title: 'Products',
            url: 'products.index',
            order: 10,
            permissions: 'MANAGE_PRODUCTS',
            route: 'products.*',
            icon: 'mdi-package-variant-closed'
        );

        // Add Categories submenu
        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'menu',
            title: 'Categories',
            url: 'dashboard.categories.index',
            order: 20,
            permissions: 'MANAGE_CATEGORIES',
            route: 'dashboard.categories.*',
            icon: 'mdi-tag-multiple'
        );

        // Add Restaurants submenu
        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'menu',
            title: 'Restaurants',
            url: 'dashboard.restaurants.index',
            order: 30,
            permissions: 'MANAGE_RESTAURANTS',
            route: 'dashboard.restaurants.*',
            icon: 'mdi-store'
        );

        // Add Menus submenu (for restaurant menus)
        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'menu',
            title: 'Restaurant Menus',
            url: 'dashboard.menus.index',
            order: 40,
            permissions: 'MANAGE_MENUS',
            route: 'dashboard.menus.*',
            icon: 'mdi-food'
        );

        // Example of popup menu item
        $menuService->addMenuItem(
            menu: 'primary',
            id: 'quick_add',
            title: 'Quick Add',
            url: '#',
            icon: 'mdi-plus-circle',
            order: 30,
            permissions: 'CREATE_ITEMS',
            route: null,
            hasSubmenu: false,
            isPopup: true,
            extra: [
                'popup_type' => 'quick_add_modal',
                'popup_size' => 'medium'
            ]
        );

        return parent::handle($request, $next);
    }
}