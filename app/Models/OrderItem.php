<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'product_name',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * 🔗 Item belongs to an order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 🔗 Item belongs to a product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 💰 Computed subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
