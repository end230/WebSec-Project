<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'access_admin_panel',
            'manage_employees',
            'manage_roles',
            'list_customers',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_orders',
            'view_customer_feedback',
            'respond_to_feedback',
            'cancel_order'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions([
            'view_customer_feedback',
            'respond_to_feedback',
            'manage_orders'
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        $customerRole->syncPermissions([
            'cancel_order'
        ]);

        // Find admin user (usually ID 1) and assign admin role
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('Admin');
        }
    }
} 