<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Product;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * ✅ ALL FIELDS FOR MASS ASSIGNMENT
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'otp_attempts',
        'is_active',
        'last_login_at',
    ];

    /**
     * Hide sensitive fields from JSON response
     */
    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    /**
     * Cast attributes to correct types
     */
    protected $casts = [
        'email_verified_at' => 'boolean',
        'otp_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'otp_attempts' => 'integer',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Helper methods
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at;
    }

    public function isOtpExpired(): bool
    {
        return $this->otp_expires_at && $this->otp_expires_at->isPast();
    }
      public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * Optional: Active products scope
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'seller_id')->where('status', 'active');
    }
}
