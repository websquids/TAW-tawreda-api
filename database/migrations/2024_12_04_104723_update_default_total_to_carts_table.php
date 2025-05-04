<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('carts', function (Blueprint $table) {
      DB::statement('ALTER TABLE carts ALTER COLUMN total SET DEFAULT 0');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('carts', function (Blueprint $table) {
      DB::statement('ALTER TABLE carts ALTER COLUMN total DROP DEFAULT');
    });
  }
};
