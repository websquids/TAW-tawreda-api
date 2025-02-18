<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('app_settings', function (Blueprint $table) {
      $table->enum('type', ['string', 'integer', 'boolean', 'array', 'object', 'html'])->default('string')->change();
      $table->boolean('is_deletable')->default(true)->after('type');
      $table->boolean('is_value_editable')->default(true)->after('is_deletable');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
        //
  }
};
