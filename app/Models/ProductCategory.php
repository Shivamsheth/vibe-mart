<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /* ======================
       SCOPES
    ====================== */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* ======================
       HELPERS
    ====================== */

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }
}
