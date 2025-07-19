<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'imageUrl',
        'quantity',
        'status',
        'size',
        'ingredients',
        'allergens',
        'extras',
        'type',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        });
        
        $query->when($filters['category_id'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });
        
        $query->when($filters['price_range'] ?? null, function ($query, $priceRange) {
            switch ($priceRange) {
                case 'under-10':
                    $query->where('price', '<', 10);
                    break;
                case '10-50':
                    $query->where('price', '>=', 10)->where('price', '<=', 50);
                    break;
                case '50-100':
                    $query->where('price', '>=', 50)->where('price', '<=', 100);
                    break;
                case '100-500':
                    $query->where('price', '>=', 100)->where('price', '<=', 500);
                    break;
                case 'over-500':
                    $query->where('price', '>', 500);
                    break;
            }
        });
        
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
        
        $query->when($filters['sort'] ?? null, function ($query, $sort) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($sort, $direction);
        });
        
        return $query;
    }
}