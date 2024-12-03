<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('brand_translations', function (Blueprint $table) {
      $table->id();
      $table->string('locale')->index();
      $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
      $table->string('name');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('brand_translations');
  }
};
