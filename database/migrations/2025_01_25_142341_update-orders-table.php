<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    // delete address_id column from orders table and add address_id column to order_addresses table
    Schema::table('orders', function (Blueprint $table) {
      $table->dropForeign(['address_id']);
      $table->dropColumn('address_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('orders', function (Blueprint $table) {
      $table->unsignedBigInteger('address_id')->after('customer_id');
    });
  }
};
