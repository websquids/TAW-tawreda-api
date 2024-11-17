<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  // Unique permission name
            $table->string('guard_name');       // Guard name (e.g., web, api)
            $table->timestamps();
        });

        // Create Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  // Unique role name
            $table->string('guard_name');      // Guard name (e.g., web, api)
            $table->timestamps();
        });

        // Create role_has_permissions pivot table
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');  // Foreign key for roles
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');  // Foreign key for permissions
            $table->timestamps();
        });

        // Create model_has_roles pivot table (for assigning roles to users)
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');  // This allows polymorphic relationships (users or any model can have roles)
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');  // Foreign key for roles
            $table->timestamps();
        });

        // Create model_has_permissions pivot table (for assigning permissions to users)
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');  // This allows polymorphic relationships (users or any model can have permissions)
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');  // Foreign key for permissions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop all the tables in reverse order of creation
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
}
