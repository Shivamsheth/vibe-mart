<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            // Primary Key
            $table->id();

            // Business Order ID
            $table->string('order_id')->unique();

            // Foreign Keys
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();

            // Order Time
            $table->timestamp('order_time')->useCurrent();

            // Payment Details
            $table->enum('method_payment', ['cash', 'card', 'upi'])->default('card');
            $table->enum('card_type', ['Visa', 'AmericanExpress', 'MasterCard'])->nullable();
            $table->json('card_details')->nullable();

            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('payment_time')->nullable();
            $table->unsignedInteger('attempts')->default(0);

            // Pickup & Dispatch
            $table->boolean('is_pickedup')->default(false);
            $table->timestamp('order_pickup')->nullable();
            $table->timestamp('order_dispatched')->nullable();

            // Order Status
            $table->enum('order_status', ['pending', 'approved', 'delivered'])->default('pending');
            $table->enum('package_status', ['in_warehouse', 'on_the_way', 'returned'])->default('in_warehouse');

            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('status_updated_at')->nullable();

            // Laravel default timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
