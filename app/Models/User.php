<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'boolean',
        'otp_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'otp_attempts' => 'integer',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ CORRECT RELATIONSHIPS (User → Others)
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    // ✅ Helper methods (KEEP THESE)
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at;
    }

    public function isOtpExpired(): bool
    {
        return $this->otp_expires_at && $this->otp_expires_at->isPast();
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'seller_id')->where('status', 'active');
    }
}
