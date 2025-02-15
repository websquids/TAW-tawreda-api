<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('app_setting_translations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('app_setting_id')->constrained('app_settings')->cascadeOnDelete();
      $table->string('locale')->index();
      $table->string('value');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('app_setting_translations');
  }
};
