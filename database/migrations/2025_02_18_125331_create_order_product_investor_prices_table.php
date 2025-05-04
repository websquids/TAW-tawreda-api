<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('order_product_investor_prices', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_product_id');
      $table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
      $table->decimal('investor_price', 10, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('order_product_investor_prices');
  }
};
