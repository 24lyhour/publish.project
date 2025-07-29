# MenuService Usage Reference

This document shows how to use the enhanced MenuService system throughout your application.

## Basic Usage in Middleware

### Add a Main Menu Item

```php
public function handle(Request $request, Closure $next)
{
    MenuService::addMenuItem(
        menu: 'primary',
        id: 'dashboard',
        title: 'Dashboard',
        url: 'dashboard', // Route name, not generated URL
        icon: 'mdi-view-dashboard',
        order: 10,
        permissions: 'VIEW_DASHBOARD',
        route: 'dashboard'
    );

    return $next($request);
}
```

### Add a Menu Item with Submenus

```php
public function handle(Request $request, Closure $next)
{
    $menuService = app(MenuService::class);

    // Add parent menu item
    $menuService->addMenuItem(
        menu: 'primary',
        id: 'menu',
        title: 'Menu',
        url: '#', // No direct URL since it has submenus
        icon: 'mdi-menu',
        order: 20,
        permissions: 'MANAGE_MENU',
        route: 'products.*',
        hasSubmenu: true
    );

    // Add submenu items
    $menuService->addSubmenuItem(
        menu: 'primary',
        parentId: 'menu',
        title: 'Products',
        url: 'products.index', // Route name, not generated URL
        order: 10,
        permissions: 'MANAGE_PRODUCTS',
        route: 'products.*',
        icon: 'mdi-package-variant-closed'
    );

    $menuService->addSubmenuItem(
        menu: 'primary',
        parentId: 'menu', 
        title: 'Categories',
        url: 'dashboard.categories.index', // Route name, not generated URL
        order: 20,
        permissions: 'MANAGE_CATEGORIES',
        route: 'dashboard.categories.*',
        icon: 'mdi-tag-multiple'
    );

    return $next($request);
}
```

### Add a Popup Menu Item

```php
public function handle(Request $request, Closure $next)
{
    MenuService::addMenuItem(
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
            'popup_size' => 'medium' // small, medium, large
        ]
    );

    return $next($request);
}
```

## Usage in Service Providers

### AppServiceProvider Example

```php
<?php

namespace App\Providers;

use App\Services\MenuService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerMenuItems();
    }

    private function registerMenuItems(): void
    {
        $menuService = app(MenuService::class);

        // User Management Menu
        $menuService->addMenuItem(
            menu: 'primary',
            id: 'users',
            title: 'Users',
            url: route('users.index'),
            icon: 'mdi-account-group',
            order: 40,
            permissions: 'MANAGE_USERS',
            route: 'users.*'
        );

        // Settings with submenus
        $menuService->addMenuItem(
            menu: 'primary',
            id: 'settings',
            title: 'Settings',
            url: '#',
            icon: 'mdi-cog',
            order: 100,
            permissions: 'MANAGE_SETTINGS',
            hasSubmenu: true
        );

        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'settings',
            title: 'General',
            url: route('settings.general'),
            order: 10,
            permissions: 'MANAGE_SETTINGS',
            route: 'settings.general',
            icon: 'mdi-cog-outline'
        );

        $menuService->addSubmenuItem(
            menu: 'primary',
            parentId: 'settings',
            title: 'Security',
            url: route('settings.security'),
            order: 20,
            permissions: 'MANAGE_SECURITY',
            route: 'settings.security',
            icon: 'mdi-security'
        );
    }
}
```

## Usage in Custom Middleware

### OrderMenuMiddleware Example

```php
<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;

class OrderMenuMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        MenuService::addMenuItem(
            menu: 'primary',
            id: 'order',
            title: 'Orders',
            url: route('dashboard.order.orders.index'),
            icon: 'mdi-cart-outline',
            order: 100,
            permissions: 'MANAGE_ORDER',
            route: 'dashboard.order.*'
        );

        MenuService::addSubmenuItem('primary', 'order', 'Orders', route('dashboard.order.orders.index'), 10, 'MANAGE_ORDER', 'dashboard.order.orders.*');
        MenuService::addSubmenuItem('primary', 'order', 'Vendors', route('dashboard.order.vendors.index'), 15, 'VIEW_ORDER_VENDORS', 'dashboard.order.vendors.*');
        MenuService::addSubmenuItem('primary', 'order', 'Coupons', route('dashboard.order.coupons.index'), 20, 'MANAGE_ORDER', 'dashboard.order.coupons.*');
        MenuService::addSubmenuItem('primary', 'order', 'ShippingFees', route('dashboard.order.shippingfees.index'), 30, 'MANAGE_ORDER', 'dashboard.order.shippingfees.*');
        MenuService::addSubmenuItem('primary', 'order', 'Delivery Zones', route('dashboard.order.delivery_zones.index'), 40, 'MANAGE_ORDER', 'dashboard.order.delivery_zones.*');
        MenuService::addSubmenuItem('primary', 'order', 'Carts', route('dashboard.order.carts.index'), 50, 'MANAGE_ORDER', 'dashboard.order.carts.*');
        MenuService::addSubmenuItem('primary', 'order', 'Refunds', route('dashboard.order.refunds.index'), 60, 'MANAGE_ORDER', 'dashboard.order.refunds.*');
        MenuService::addSubmenuItem('primary', 'order', 'Reviews', route('dashboard.order.reviews.index'), 70, 'MANAGE_ORDER', 'dashboard.order.reviews.*');
        MenuService::addSubmenuItem('primary', 'order', 'Transactions', route('dashboard.order.transactions.index'), 80, 'MANAGE_ORDER', 'dashboard.order.transactions.*');

        return $next($request);
    }
}
```

## Available Parameters

### addMenuItem Parameters

- `menu`: The menu group identifier (e.g., 'primary')
- `id`: Unique menu item ID
- `title`: Display title
- `url`: URL/route
- `icon`: Material Design Icon (e.g., 'mdi-home')
- `order`: Display order (lower = higher in menu)
- `permissions`: Permission required to view
- `route`: Route pattern for active state (optional)
- `hasSubmenu`: Whether this item has submenus (default: false)
- `isPopup`: Whether this should open as popup/modal (default: false)
- `extra`: Extra configuration array (default: [])

### addSubmenuItem Parameters

- `menu`: The menu group identifier
- `parentId`: Parent menu item ID
- `title`: Submenu title
- `url`: Submenu URL/route
- `order`: Display order (lower = higher in submenu)
- `permissions`: Required permissions
- `route`: Route pattern for active state (optional)
- `icon`: Optional icon (default: null)
- `isPopup`: Whether this should open as popup/modal (default: false)
- `extra`: Extra configuration array (default: [])

## Popup Configuration

### Popup Types

- `quick_add_modal`: Quick add form
- `custom_modal`: Custom popup content
- `info_modal`: Information display

### Popup Sizes

- `small`: 400px width
- `medium`: 500px width (default)
- `large`: 800px width

## Icon Reference

Common Material Design Icons:
- `mdi-view-dashboard` - Dashboard
- `mdi-package-variant-closed` - Products
- `mdi-tag-multiple` - Categories
- `mdi-store` - Restaurants/Stores
- `mdi-food` - Food/Menu items
- `mdi-cart-outline` - Orders/Shopping
- `mdi-account-group` - Users
- `mdi-cog` - Settings
- `mdi-plus-circle` - Add/Create
- `mdi-menu` - Menu/Navigation

## Best Practices

1. **Use consistent ordering**: Start with 10, increment by 10 (10, 20, 30...)
2. **Group related items**: Use submenus for related functionality
3. **Meaningful permissions**: Use descriptive permission names
4. **Route patterns**: Use wildcards for route matching (e.g., 'products.*')
5. **Icon consistency**: Use similar icon styles throughout
6. **Popup sparingly**: Only use popups for quick actions or forms

## Current Menu Structure

After implementing this system, your menu will look like:

```
Dashboard
Menu
├── Products
├── Categories  
├── Restaurants
└── Restaurant Menus
Quick Add (Popup)
```