<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'order_id',
        'customer_id',
        'method_payment',
        'payment_status',
        'total_amount',
        'address',
        'phone',
        'email',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * 🔗 Order belongs to a customer (user)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * 🔗 Order has many items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ✅ Helpers
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}
