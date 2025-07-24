<?php

namespace App\Transform\Dashboard\Inertia\V1;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * RestaurantTransform
 * 
 * Handles data transformation for Restaurant models
 * Separates transformation logic from controllers
 */
class RestaurantTransform
{
    /**
     * Transform single restaurant for frontend
     * 
     * @param Restaurant $restaurant
     * @return array
     */
    public static function single(Restaurant $restaurant): array
    {
        return [
            // Basic properties
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'address' => $restaurant->address,
            'phone' => $restaurant->phone,
            'email' => $restaurant->email,
            'description' => $restaurant->description,
            'status' => $restaurant->status ?? 'active',

            // Relationships
            'menus_count' => $restaurant->menus()->count(),

            // Computed properties
            'imageUrl' => static::getImageUrl($restaurant),
            'statusColor' => static::getStatusColor($restaurant->status),

            // Metadata
            'created_at' => $restaurant->created_at,
            'updated_at' => $restaurant->updated_at,
            'created_at_formatted' => static::formatDate($restaurant->created_at),
            'updated_at_formatted' => static::formatDate($restaurant->updated_at),

            // UI helpers
            'displayName' => static::getDisplayName($restaurant),
            'shortDescription' => static::getShortDescription($restaurant->description),
            'badges' => static::getBadges($restaurant),
            'actions' => static::getAvailableActions($restaurant)
        ];
    }

    /**
     * Transform collection of restaurants
     * 
     * @param Collection $restaurants
     * @return array
     */
    public static function collection(Collection $restaurants): array
    {
        return $restaurants->map(function ($restaurant) {
            return static::single($restaurant);
        })->toArray();
    }

    /**
     * Transform paginated restaurants
     * 
     * @param LengthAwarePaginator $paginatedRestaurants
     * @return LengthAwarePaginator
     */
    public static function paginated(LengthAwarePaginator $paginatedRestaurants): LengthAwarePaginator
    {
        $paginatedRestaurants->through(function ($restaurant) {
            return static::single($restaurant);
        });

        return $paginatedRestaurants;
    }

    /**
     * Transform for form editing
     * 
     * @param Restaurant $restaurant
     * @return array
     */
    public static function forForm(Restaurant $restaurant): array
    {
        return [
            'name' => $restaurant->name ?? '',
            'address' => $restaurant->address ?? '',
            'phone' => $restaurant->phone ?? '',
            'email' => $restaurant->email ?? '',
            'description' => $restaurant->description ?? '',
            'status' => $restaurant->status ?? 'active',
            'imageUrl' => $restaurant->imageUrl ?? '',
        ];
    }

    /**
     * Transform for index listing (lighter version)
     * 
     * @param Restaurant $restaurant
     * @return array
     */
    public static function forIndex(Restaurant $restaurant): array
    {
        return [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'address' => $restaurant->address,
            'phone' => $restaurant->phone,
            'email' => $restaurant->email,
            'description' => static::getShortDescription($restaurant->description, 100),
            'imageUrl' => static::getImageUrl($restaurant),
            'status' => $restaurant->status ?? 'active',
            'statusColor' => static::getStatusColor($restaurant->status),
            'menus_count' => $restaurant->menus()->count(),
            'created_at' => $restaurant->created_at,
            'updated_at' => $restaurant->updated_at,
            'badges' => static::getBadges($restaurant)
        ];
    }

    // Helper methods

    /**
     * Get image URL
     */
    protected static function getImageUrl(Restaurant $restaurant): ?string
    {
        if (!$restaurant->imageUrl) return null;

        // If it's a full URL, return as is
        if (str_starts_with($restaurant->imageUrl, 'http')) {
            return $restaurant->imageUrl;
        }

        // Otherwise, construct the URL
        return asset("storage/restaurants/{$restaurant->imageUrl}");
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
    protected static function getDisplayName(Restaurant $restaurant): string
    {
        return $restaurant->name ?: 'Unnamed Restaurant';
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
     * Get badges for restaurant display
     */
    protected static function getBadges(Restaurant $restaurant): array
    {
        $badges = [];

        // Status badge
        $badges[] = [
            'text' => $restaurant->status ?? 'active',
            'color' => static::getStatusColor($restaurant->status),
            'icon' => ($restaurant->status ?? 'active') === 'active' ? 'mdi-check-circle' : 'mdi-close-circle'
        ];

        // Menus count badge
        $menusCount = $restaurant->menus()->count();
        $badges[] = [
            'text' => $menusCount . ' Menus',
            'color' => $menusCount > 0 ? 'info' : 'grey',
            'icon' => 'mdi-menu'
        ];

        // Contact info badge
        if ($restaurant->phone || $restaurant->email) {
            $badges[] = [
                'text' => 'Has Contact',
                'color' => 'primary',
                'icon' => 'mdi-phone'
            ];
        }

        return $badges;
    }

    /**
     * Get available actions for this restaurant
     */
    protected static function getAvailableActions(Restaurant $restaurant): array
    {
        return [
            [
                'name' => 'view',
                'label' => 'View',
                'icon' => 'mdi-eye',
                'color' => 'primary',
                'route' => 'dashboard.restaurants.show'
            ],
            [
                'name' => 'edit',
                'label' => 'Edit',
                'icon' => 'mdi-pencil',
                'color' => 'warning',
                'route' => 'dashboard.restaurants.edit'
            ],
            [
                'name' => 'delete',
                'label' => 'Delete',
                'icon' => 'mdi-delete',
                'color' => 'error',
                'route' => 'dashboard.restaurants.delete'
            ]
        ];
    }
}