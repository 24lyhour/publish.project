<?php

namespace App\Transform\Dashboard\Inertia\V1;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

/**
 * ProductTransform
 * 
 * Handles data transformation for Product models
 * Separates transformation logic from controllers
 */
class ProductTransform
{
    /**
     * Transform single product for frontend
     * 
     * @param Product $product
     * @return array
     */
    public static function single(Product $product): array
    {
        return [
            // Basic properties
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => static::formatPrice($product->price),
            'quantity' => $product->quantity ?? 0,
            'status' => $product->status ?? 'active',
            'size' => $product->size,
            'type' => $product->type,
            'ingredients' => $product->ingredients,

            // Relationships
            'category' => static::transformCategory($product->category),
            'category_id' => $product->category_id,

            // Computed properties
            'imageUrl' => static::getImageUrl($product),
            'formattedPrice' => static::getFormattedPrice($product->price),
            'statusColor' => static::getStatusColor($product->status),
            'stockStatus' => static::getStockStatus($product->quantity),
            'isLowStock' => static::isLowStock($product->quantity),
            'categoryName' => static::getCategoryName($product),

            // Metadata
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'created_at_formatted' => static::formatDate($product->created_at),
            'updated_at_formatted' => static::formatDate($product->updated_at),

            // UI helpers
            'displayName' => static::getDisplayName($product),
            'shortDescription' => static::getShortDescription($product->description),
            'badges' => static::getBadges($product),
            'actions' => static::getAvailableActions($product)
        ];
    }

    /**
     * Transform collection of products
     * 
     * @param Collection $products
     * @return array
     */
    public static function collection(Collection $products): array
    {
        return $products->map(function ($product) {
            return static::single($product);
        })->toArray();
    }

    /**
     * Transform paginated products
     * 
     * @param LengthAwarePaginator $paginatedProducts
     * @return LengthAwarePaginator
     */
    public static function paginated(LengthAwarePaginator $paginatedProducts): LengthAwarePaginator
    {
        $paginatedProducts->through(function ($product) {
            return static::single($product);
        });

        return $paginatedProducts;
    }

    /**
     * Transform for form editing
     * 
     * @param Product $product
     * @return array
     */
    public static function forForm(Product $product): array
    {
        return [
            'name' => $product->name ?? '',
            'description' => $product->description ?? '',
            'price' => $product->price ?? '',
            'category_id' => $product->category_id,
            'quantity' => $product->quantity ?? '',
            'status' => $product->status ?? 'active',
            'size' => $product->size ?? '',
            'type' => $product->type ?? '',
            'ingredients' => $product->ingredients ?? '',
            'image' => null // Always null for file inputs
        ];
    }

    /**
     * Transform for index listing (lighter version)
     * 
     * @param Product $product
     * @return array
     */
    public static function forIndex(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => static::getShortDescription($product->description, 100),
            'price' => static::formatPrice($product->price),
            'formattedPrice' => static::getFormattedPrice($product->price),
            'category' => static::getCategoryName($product),
            'category_id' => $product->category_id,
            'imageUrl' => static::getImageUrl($product),
            'quantity' => $product->quantity ?? 0,
            'status' => $product->status ?? 'active',
            'statusColor' => static::getStatusColor($product->status),
            'isLowStock' => static::isLowStock($product->quantity),
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'badges' => static::getBadges($product)
        ];
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
     * Transform category relationship
     */
    protected static function transformCategory($category): ?array
    {
        if (!$category) return null;
        
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug ?? null,
            'description' => $category->description ?? null
        ];
    }

    /**
     * Get category name
     */
    protected static function getCategoryName(Product $product): string
    {
        return $product->category?->name ?? 'Uncategorized';
    }

    /**
     * Get image URL
     */
    protected static function getImageUrl(Product $product): ?string
    {
        // Use imageUrl field from database, not image
        if (!$product->imageUrl) return null;

        // If it's a full URL, return as is
        if (filter_var($product->imageUrl, FILTER_VALIDATE_URL)) {
            return $product->imageUrl;
        }

        // Otherwise, construct the URL using Storage facade
        return Storage::url($product->imageUrl);
    }

    /**
     * Get status color for UI
     */
    protected static function getStatusColor($status): string
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'error',
            'draft' => 'warning',
            'archived' => 'grey'
        ];

        return $colors[$status] ?? 'grey';
    }

    /**
     * Get stock status
     */
    protected static function getStockStatus($quantity): string
    {
        $qty = $quantity ?? 0;
        
        if ($qty === 0) return 'out_of_stock';
        if ($qty <= 5) return 'low_stock';
        if ($qty <= 20) return 'medium_stock';
        return 'in_stock';
    }

    /**
     * Check if product is low stock
     */
    protected static function isLowStock($quantity): bool
    {
        return ($quantity ?? 0) <= 5;
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
    protected static function getDisplayName(Product $product): string
    {
        return $product->name ?: 'Unnamed Product';
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
     * Get badges for product display
     */
    protected static function getBadges(Product $product): array
    {
        $badges = [];

        // Status badge
        $badges[] = [
            'text' => $product->status ?? 'active',
            'color' => static::getStatusColor($product->status),
            'icon' => ($product->status ?? 'active') === 'active' ? 'mdi-check-circle' : 'mdi-close-circle'
        ];

        // Stock badge
        if (static::isLowStock($product->quantity)) {
            $badges[] = [
                'text' => 'Low Stock',
                'color' => 'warning',
                'icon' => 'mdi-alert'
            ];
        }

        // Price badge
        $badges[] = [
            'text' => static::getFormattedPrice($product->price),
            'color' => 'primary',
            'icon' => 'mdi-currency-usd'
        ];

        return $badges;
    }

    /**
     * Get available actions for this product
     */
    protected static function getAvailableActions(Product $product): array
    {
        $actions = [
            [
                'name' => 'view',
                'label' => 'View',
                'icon' => 'mdi-eye',
                'color' => 'primary',
                'route' => 'products.show'
            ],
            [
                'name' => 'edit',
                'label' => 'Edit',
                'icon' => 'mdi-pencil',
                'color' => 'warning',
                'route' => 'products.edit'
            ]
        ];

        // Only add delete if not a critical product
        if ($product->status !== 'critical') {
            $actions[] = [
                'name' => 'delete',
                'label' => 'Delete',
                'icon' => 'mdi-delete',
                'color' => 'error',
                'route' => 'products.delete'
            ];
        }

        return $actions;
    }
}