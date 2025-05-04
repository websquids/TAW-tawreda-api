<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    // add storage_discount for products table
    Schema::table('products', function (Blueprint $table) {
      $table->decimal('storage_discount', 8, 2)->default(0)->after('max_storage_quantity');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    // remove storage_discount from products table
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn('storage_discount');
    });
  }
};
