<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Create employee user if not exists
        $employee = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
            ]
        );
        $employee->assignRole('Employee');

        // Create customer users if not exists
        $customer1 = User::firstOrCreate(
            ['email' => 'customer1@example.com'],
            [
                'name' => 'Customer One',
                'password' => Hash::make('password'),
                'credits' => 5000,
            ]
        );
        $customer1->assignRole('Customer');

        $customer2 = User::firstOrCreate(
            ['email' => 'customer2@example.com'],
            [
                'name' => 'Customer Two',
                'password' => Hash::make('password'),
                'credits' => 8000,
            ]
        );
        $customer2->assignRole('Customer');

        // Run permission seeder to ensure all permissions are properly assigned
        $this->call(PermissionSeeder::class);
        
        // Seed products
        $this->call(ProductSeeder::class);
    }
}
