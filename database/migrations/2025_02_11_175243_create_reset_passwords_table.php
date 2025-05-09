<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('reset_passwords', function (Blueprint $table) {
      $table->id();
      $table->string('identifier')->index();
      $table->enum('identifier_type', ['email', 'phone'])->index();
      $table->string('token');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('reset_passwords');
  }
};
