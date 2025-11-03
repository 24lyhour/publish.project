# Inertia Modal Implementation Guide

## Overview
This project uses a custom Inertia modal implementation to display forms and dialogs as modals instead of full page components.

## Implementation Details

### 1. Service Provider
Created `app/Providers/InertiaModalServiceProvider.php` that adds modal functionality to Inertia:
- Extends Inertia ResponseFactory with `modal()` method
- Adds `baseRoute()` method to Response objects
- Automatically includes `isModal: true` prop in modal responses

### 2. Controller Usage
In controllers, use the modal syntax:

```php
// Create modal
public function create()
{
    return Inertia::modal('Dashboard/Inertia/V1/Products/Create', [
        'categories' => Category::all(['id', 'name']),
    ])->baseRoute('products.index');
}

// Edit modal
public function edit(Product $product)
{
    return Inertia::modal('Dashboard/Inertia/V1/Products/Edit', [
        'product' => ProductTransform::single($product),
        'categories' => Category::all(['id', 'name']),
    ])->baseRoute('products.index');
}

// Delete confirmation modal
public function delete(Product $product)
{
    return Inertia::modal('Dashboard/Inertia/V1/Products/Delete', [
        'product' => ProductTransform::single($product),
    ])->baseRoute('products.index');
}
```

### 3. Vue Components
Modal components should:
- Use `SakalFormModal` as the modal wrapper
- Access the `isModal` prop to determine if rendered as modal
- Use the `baseRoute` prop for navigation after actions

### 4. Key Files
- **Service Provider**: `app/Providers/InertiaModalServiceProvider.php`
- **Registration**: `bootstrap/providers.php`
- **Example Controller**: `app/Http/Controllers/ProductController.php`
- **Vue Components**: `resources/js/Pages/Dashboard/Inertia/V1/Products/*.vue`

### 5. How It Works
1. Controller calls `Inertia::modal()` instead of `Inertia::render()`
2. Service provider intercepts and adds `isModal: true` to props
3. `baseRoute()` method adds navigation information
4. Vue components receive these props and render accordingly

### 6. Benefits
- Consistent modal behavior across the application
- Easy to switch between modal and full-page rendering
- Maintains Inertia's SPA navigation benefits
- No external packages required

### 7. Notes
- Always clear cache after modifying service providers: `php artisan cache:clear`
- The `baseRoute` is used to redirect after form submission
- Modal components can check `isModal` prop to conditionally render UI