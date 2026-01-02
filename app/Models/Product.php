<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'sale_price',
        'cost_price',
        'brand',
        'stock_quantity',
        'stock_alert',
        'manage_stock',
        'status',
        'is_visible',
        'is_featured',
    ];

    protected $casts = [
        'price'          => 'float',
        'sale_price'     => 'float',
        'cost_price'     => 'float',
        'stock_quantity' => 'integer',
        'stock_alert'    => 'integer',
        'manage_stock'   => 'boolean',
        'is_visible'     => 'boolean',
        'is_featured'    => 'boolean',
    ];

    /* ======================
       ROUTE BINDING
    ====================== */

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /* ======================
       RELATIONSHIPS
    ====================== */

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')
                    ->where('is_primary', true);
    }

    /* ======================
       ACCESSORS
    ====================== */

    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price;
    }

    public function getMainImageAttribute()
    {
        return $this->primaryImage()->first()
            ?? $this->images()->first();
    }

    /* ======================
       STOCK HELPERS
    ====================== */

    public function inStock(): bool
    {
        return !$this->manage_stock || $this->stock_quantity > 0;
    }

    public function lowStock(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= $this->stock_alert;
    }

    /* ======================
       SCOPES
    ====================== */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
