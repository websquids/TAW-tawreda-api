<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder {
  public function run() {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $resources = [
      'category',
      'unit',
      'brand',
      'product',
      'role',
      'order',
      'cart',
      'address',
      'sliders',
      'app_settings',
    ];

    foreach ($resources as $resource) {
      $this->createPermission("view $resource");
      $this->createPermission("create $resource");
      $this->createPermission("edit $resource");
      $this->createPermission("delete $resource");
    }

    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $adminRole->givePermissionTo(Permission::all());

    $customerPermissions = [
      'view product',
      'view cart',
      'create cart',
      'edit cart',
      'view address',
      'create address',
      'edit address',
      'view order',
      'create order',
      'view app_settings',
    ];

    foreach ($customerPermissions as $permission) {
      $this->createPermission($permission);
    }

    $customerRole = Role::firstOrCreate(['name' => 'customer']);
    $customerRole->givePermissionTo($customerPermissions);
  }

  /**
   * Create a permission if it doesn't already exist.
   *
   * @param string $permissionName
   */
  private function createPermission(string $permissionName) {
    Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
  }
}
