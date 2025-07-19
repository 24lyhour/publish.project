<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /** @use HasFactory<\Database\Factories\RestaurantFactory> */
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',           // Restaurant name
        'address',        // Address of the restaurant
        'phone',          // Contact phone number
        'email',          // Contact email
        'imageUrl',       // Image or logo URL
        'description',    // Description
        'status',         // Status (active/inactive)
    ];

    /**
     * Menus relationship
     * 
     * 
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurant_id');
    }
}
