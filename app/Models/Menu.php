<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_name',
        'category_id',
        'price',
        'restaurant_id',
        'imageUrl',
        'description',
        'status',
    ];

    // Menu belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Menu belongs to a Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}