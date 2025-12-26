<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create ENUM type for user roles in PostgreSQL
        DB::unprepared('
            DO $$ BEGIN
                CREATE TYPE user_type_enum AS ENUM (\'admin\', \'customer\', \'seller\');
            EXCEPTION
                WHEN duplicate_object THEN null;
            END $$;
        ');

        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            
            $table->enum('type',['admin','customer','seller'])->default('customer')->comment('User role: admin, customer, or seller');
            
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('country');
            
            $table->string('otp')->nullable()->comment('One-time password for email verification');
            $table->timestamp('otp_expires_at')->nullable()->comment('OTP expiration timestamp');
            $table->boolean('email_verified_at')->default(false)->comment('Email verification status');
            $table->integer('otp_attempts')->default(0)->comment('Number of OTP verification attempts');
            
            $table->boolean('is_active')->default(true)->comment('User account status');
            $table->timestamp('last_login_at')->nullable()->comment('Last login timestamp');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('email');
            $table->index('phone');
            $table->index('type');
            $table->index('email_verified_at');
            $table->index('created_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('otp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('otp');
            $table->enum('status', ['sent', 'verified', 'expired', 'failed'])->default('sent');
            $table->ipAddress('ip_address')->nullable()->comment('IP address of OTP request');
            $table->string('user_agent')->nullable()->comment('User agent/device info');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('expires_at')->comment('OTP expiration time');
            $table->timestamp('verified_at')->nullable()->comment('When OTP was verified');
            
            $table->index('user_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('otp_logs');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        // Drop the ENUM type
        DB::unprepared('DROP TYPE IF EXISTS user_type_enum CASCADE;');
    }
};
