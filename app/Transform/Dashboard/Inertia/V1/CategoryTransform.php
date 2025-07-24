<?php

namespace App\Transform\Dashboard\Inertia\V1;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * CategoryTransform
 * 
 * Handles data transformation for Category models
 * Separates transformation logic from controllers
 */
class CategoryTransform
{
    /**
     * Transform single category for frontend
     * 
     * @param Category $category
     * @return array
     */
    public static function single(Category $category): array
    {
        return [
            // Basic properties
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'type' => $category->type,
            'menu_id' => $category->menu_id,
            'price_sale' => static::formatPrice($category->price_sale),
            'status' => $category->status ?? 'active',

            // Relationships
            'menu' => static::transformMenu($category->menu),
            'products_count' => $category->products()->count(),

            // Computed properties
            'imageUrl' => static::getImageUrl($category),
            'formattedPriceSale' => static::getFormattedPrice($category->price_sale),
            'statusColor' => static::getStatusColor($category->status),
            'menuName' => static::getMenuName($category),

            // Metadata
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'created_at_formatted' => static::formatDate($category->created_at),
            'updated_at_formatted' => static::formatDate($category->updated_at),

            // UI helpers
            'displayName' => static::getDisplayName($category),
            'shortDescription' => static::getShortDescription($category->description),
            'badges' => static::getBadges($category),
            'actions' => static::getAvailableActions($category)
        ];
    }

    /**
     * Transform collection of categories
     * 
     * @param Collection $categories
     * @return array
     */
    public static function collection(Collection $categories): array
    {
        return $categories->map(function ($category) {
            return static::single($category);
        })->toArray();
    }

    /**
     * Transform for select options
     * 
     * @param Collection $categories
     * @return array
     */
    public static function forSelect(Collection $categories): array
    {
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'value' => $category->id,
                'title' => $category->name
            ];
        })->toArray();
    }

    /**
     * Transform paginated categories
     * 
     * @param LengthAwarePaginator $paginatedCategories
     * @return LengthAwarePaginator
     */
    public static function paginated(LengthAwarePaginator $paginatedCategories): LengthAwarePaginator
    {
        $paginatedCategories->through(function ($category) {
            return static::single($category);
        });

        return $paginatedCategories;
    }

    // Helper methods

    /**
     * Format price for display
     */
    protected static function formatPrice($price): string
    {
        if (!$price) return '0.00';
        return number_format((float)$price, 2, '.', '');
    }

    /**
     * Get formatted price with currency
     */
    protected static function getFormattedPrice($price): string
    {
        return '$' . static::formatPrice($price);
    }

    /**
     * Transform menu relationship
     */
    protected static function transformMenu($menu): ?array
    {
        if (!$menu) return null;
        
        return [
            'id' => $menu->id,
            'name' => $menu->name,
            'description' => $menu->description ?? null
        ];
    }

    /**
     * Get menu name
     */
    protected static function getMenuName(Category $category): string
    {
        return $category->menu?->name ?? 'No Menu';
    }

    /**
     * Get image URL
     */
    protected static function getImageUrl(Category $category): ?string
    {
        if (!$category->imageUrl) return null;

        // If it's a full URL, return as is
        if (str_starts_with($category->imageUrl, 'http')) {
            return $category->imageUrl;
        }

        // Otherwise, construct the URL
        return asset("storage/categories/{$category->imageUrl}");
    }

    /**
     * Get status color for UI
     */
    protected static function getStatusColor($status): string
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'error',
            'draft' => 'warning'
        ];

        return $colors[$status] ?? 'grey';
    }

    /**
     * Format date for display
     */
    protected static function formatDate($dateString): ?string
    {
        if (!$dateString) return null;
        
        try {
            return $dateString->format('M j, Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get display name (with fallback)
     */
    protected static function getDisplayName(Category $category): string
    {
        return $category->name ?: 'Unnamed Category';
    }

    /**
     * Get short description for lists
     */
    protected static function getShortDescription($description, int $length = 150): string
    {
        $desc = $description ?: 'No description available';
        return strlen($desc) > $length ? substr($desc, 0, $length) . '...' : $desc;
    }

    /**
     * Get badges for category display
     */
    protected static function getBadges(Category $category): array
    {
        $badges = [];

        // Status badge
        $badges[] = [
            'text' => $category->status ?? 'active',
            'color' => static::getStatusColor($category->status),
            'icon' => ($category->status ?? 'active') === 'active' ? 'mdi-check-circle' : 'mdi-close-circle'
        ];

        // Products count badge
        $productsCount = $category->products()->count();
        $badges[] = [
            'text' => $productsCount . ' Products',
            'color' => $productsCount > 0 ? 'info' : 'grey',
            'icon' => 'mdi-format-list-bulleted'
        ];

        // Price badge (if has sale price)
        if ($category->price_sale) {
            $badges[] = [
                'text' => static::getFormattedPrice($category->price_sale),
                'color' => 'primary',
                'icon' => 'mdi-currency-usd'
            ];
        }

        return $badges;
    }

    /**
     * Get available actions for this category
     */
    protected static function getAvailableActions(Category $category): array
    {
        return [
            [
                'name' => 'view',
                'label' => 'View',
                'icon' => 'mdi-eye',
                'color' => 'primary',
                'route' => 'dashboard.categories.show'
            ],
            [
                'name' => 'edit',
                'label' => 'Edit',
                'icon' => 'mdi-pencil',
                'color' => 'warning',
                'route' => 'dashboard.categories.edit'
            ],
            [
                'name' => 'delete',
                'label' => 'Delete',
                'icon' => 'mdi-delete',
                'color' => 'error',
                'route' => 'dashboard.categories.delete'
            ]
        ];
    }
}