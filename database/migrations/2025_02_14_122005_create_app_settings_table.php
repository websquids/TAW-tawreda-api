<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('app_settings', function (Blueprint $table) {
      $table->id();
      $table->string('key')->unique();
      $table->enum('type', ['string', 'integer', 'boolean', 'array', 'object'])->defaultValue('string');
      $table->boolean('has_translation')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('app_settings');
  }
};
