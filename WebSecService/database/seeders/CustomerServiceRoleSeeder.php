<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerServiceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Customer Service role...');
        
        // Create Customer Service role if it doesn't exist
        $customerServiceRole = Role::firstOrCreate(
            ['name' => 'Customer Service'],
            ['management_level' => 'low']
        );
        
        // Ensure all required permissions exist
        $permissions = [
            'view_customer_feedback',
            'view_feedback', 
            'respond_to_feedback',
            'view_order_cancellations',
            'receive_cancellation_notifications',
            'manage_notifications',
        ];
        
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        
        // Assign customer feedback permissions to Customer Service role
        $customerServiceRole->syncPermissions($permissions);
        
        $this->command->info('Customer Service role created successfully with Low management level!');
        $this->command->info('Assigned permissions: ' . implode(', ', $permissions));
        
        // Create a sample Customer Service user for testing
        $customerServiceUser = User::firstOrCreate(
            ['email' => 'customerservice@example.com'],
            [
                'name' => 'Customer Service Rep',
                'password' => bcrypt('password'),
                'management_level' => 'low',
                'email_verified_at' => now(),
            ]
        );
        
        // Assign Customer Service role to the user
        if (!$customerServiceUser->hasRole('Customer Service')) {
            $customerServiceUser->assignRole('Customer Service');
            $this->command->info('Created sample Customer Service user: customerservice@example.com');
        } else {
            $this->command->info('Customer Service user already exists: customerservice@example.com');
        }
    }
}
