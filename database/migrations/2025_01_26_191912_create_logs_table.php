<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('logs', function (Blueprint $table) {
      $table->id();
      $table->string('level'); // Log level: 'info', 'warning', 'error', etc.
      $table->string('message');
      $table->json('context');
      $table->ipAddress('ip_address');
      $table->string('user_agent');
      $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('logs');
  }
};
