<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_logs',
            'access_admin_panel',
            'admin_users',
            'show_users',
            'edit_users',
            'delete_users',
            'create_employee',
            'assign_management_level',

            // Employee permissions
            'view_products',
            'manage_orders',
            'view_customers',
            'manage_feedback',

            // Feedback permissions
            'view_customer_feedback',
            'view_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'manage_notifications',
            
            // Order cancellation permission
            'cancel_order',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('Admin');
        $employeeRole = Role::findByName('Employee');
        $customerRole = Role::findByName('Customer');
        
        // Add permissions to Admin role
        $adminRole->givePermissionTo([
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_logs',
            'access_admin_panel',
            'admin_users',
            'show_users',
            'edit_users',
            'delete_users',
            'create_employee',
            'assign_management_level',

            'view_customer_feedback',
            'view_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'manage_notifications',
            'cancel_order',
        ]);
        
        // Add permissions to Employee role
        $employeeRole->givePermissionTo([
            'view_products',
            'manage_orders',
            'view_customers',
            'manage_feedback',

            'view_customer_feedback',
            'view_feedback',
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'cancel_order',
        ]);
        
        // Add permissions to Customer role
        $customerRole->givePermissionTo([
            'cancel_order',
        ]);
    }
}