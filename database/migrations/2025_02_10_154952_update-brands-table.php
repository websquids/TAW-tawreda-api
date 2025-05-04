<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    // soft delete for brands table
    Schema::table('brands', function (Blueprint $table) {
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    // restore deleted brands table
    Schema::table('brands', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });
  }
};
