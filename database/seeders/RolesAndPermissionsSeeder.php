<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define resource permissions
        $resources = [
            'category',
            'unit',
            'brand',
            'product',
            'role',
            'order',
            'cart',
            'address',
        ];

        // Create permissions if they don't already exist
        foreach ($resources as $resource) {
            $this->createPermission("view $resource");
            $this->createPermission("create $resource");
            $this->createPermission("edit $resource");
            $this->createPermission("delete $resource");
        }

        // Create admin role if it doesn't already exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Define customer-specific permissions
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
        ];

        // Ensure customer-specific permissions exist
        foreach ($customerPermissions as $permission) {
            $this->createPermission($permission);
        }

        // Create customer role if it doesn't already exist
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->givePermissionTo($customerPermissions);
    }

    /**
     * Create a permission if it doesn't already exist.
     *
     * @param string $permissionName
     */
    private function createPermission(string $permissionName)
    {
        Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
    }
}
