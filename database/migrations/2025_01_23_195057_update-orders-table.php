<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    // Update orders table to add a new column named "address_id"
    Schema::table('orders', function (Blueprint $table) {
      $table->unsignedBigInteger('address_id')->after('user_id'); // Corrected line
      $table->foreign('address_id')->references('id')->on('addresses');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropForeign('orders_address_id_foreign');
      $table->dropColumn('address_id');
    });
  }
};
