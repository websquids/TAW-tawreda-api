<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('description_en');
            $table->text('description_ar');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->integer('current_stock_quantity');
            $table->foreignId('category_id');
            $table->foreignId('brand_id');
            $table->foreignId('unit_id');
            $table->integer('min_order_quantity');
            $table->integer('max_order_quantity');
            $table->integer('min_storage_quantity');
            $table->integer('max_storage_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
