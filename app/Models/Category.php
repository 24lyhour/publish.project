<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * 
     */
    protected $fillable = [
        'name',          
        'type',          
        'menu_id',        
        'price_sale',    
        'description',    
        'status',         
        'imageUrl',         

    ];
    
    /**
     * Menu relationship 
     * 
     * 
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Product relationship
     * 
     * 
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}