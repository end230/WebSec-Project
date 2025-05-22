<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'show_users',
            'edit_users',
            'delete_users',
            'admin_users',

            // Product management
            'add_products',
            'edit_products',
            'delete_products',
            'list_products',

            // Customer management
            'list_customers',

            // Employee management
            'manage_employees',

            // Order management
            'place_order',
            'view_orders',
            'manage_orders',
            'cancel_order'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Admin role
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Employee role
        $employeeRole = Role::create(['name' => 'Employee']);
        $employeeRole->givePermissionTo([
            'list_products',
            'add_products',
            'edit_products',
            'delete_products',
            'list_customers',
            'view_orders',
            'manage_orders',
            'cancel_order'
        ]);

        // Customer role
        $customerRole = Role::create(['name' => 'Customer']);
        $customerRole->givePermissionTo([
            'list_products',
            'place_order',
            'cancel_order'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove roles and permissions
        $roles = ['Admin', 'Employee', 'Customer'];
        foreach ($roles as $role) {
            $role = Role::findByName($role);
            if ($role) {
                $role->delete();
            }
        }

        // Delete all permissions
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $permission->delete();
        }
    }
};
