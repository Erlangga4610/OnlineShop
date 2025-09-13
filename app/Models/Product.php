<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'image',
        'stock',
        'price',
        'description',
        'brand_id',
        'category_id',
    ];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
