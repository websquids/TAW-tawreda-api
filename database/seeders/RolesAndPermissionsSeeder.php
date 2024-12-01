<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder {
  public function run() {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $permissions = [
      'category',
      'unit',
      'brand',
      'product',
      'role',
    ];
    foreach ($permissions as $permission) {
      Permission::create(['name' => "view $permission"]);
      Permission::create(['name' => "create $permission"]);
      Permission::create(['name' => "edit $permission"]);
      Permission::create(['name' => "delete $permission"]);
    }

    $adminRole = Role::create(['name' => 'admin']);

    $adminRole->givePermissionTo(Permission::all());
  }
}
