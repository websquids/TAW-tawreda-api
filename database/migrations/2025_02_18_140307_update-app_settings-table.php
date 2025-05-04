<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    // change is_value_editable to is_key_editable in app_settings table
    Schema::table('app_settings', function (Blueprint $table) {
      $table->renameColumn('is_value_editable', 'is_key_editable');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
        //
  }
};
