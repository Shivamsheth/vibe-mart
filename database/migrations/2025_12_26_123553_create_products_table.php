<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seller_id')->constrained('users'); // each product belongs to a seller
            $table->foreignId('category_id')->constrained('product_categories');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();

            $table->text('description')->nullable();
            $table->string('short_description', 255)->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();

            $table->string('brand', 100)->nullable();

            $table->integer('stock_quantity')->default(0);
            $table->integer('stock_alert')->default(0);
            $table->boolean('manage_stock')->default(true);

            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
