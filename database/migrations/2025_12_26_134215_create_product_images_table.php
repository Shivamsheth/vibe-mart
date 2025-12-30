<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            // each image belongs to one product
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            // Cloudinary URL or public_id
            $table->string('path');

            // first image can be marked as primary
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
