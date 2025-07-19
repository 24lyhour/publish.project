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
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'slug' => $category->slug ?? null,
            'status' => $category->status ?? 'active',
            'sort_order' => $category->sort_order ?? 0,
            'products_count' => $category->products_count ?? 0,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'created_at_formatted' => static::formatDate($category->created_at),
            'updated_at_formatted' => static::formatDate($category->updated_at),
            'statusColor' => static::getStatusColor($category->status),
            'displayName' => static::getDisplayName($category)
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
}