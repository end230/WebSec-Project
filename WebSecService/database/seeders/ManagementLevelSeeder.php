<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ManagementLevelSeeder extends Seeder
{
    /**
     * Assign management levels to users based on their roles.
     * - High: Full access to all features (but still respecting permissions)
     * - Middle: Handle both low-level tasks and customer tasks
     * - Low: Only handle customer feedback or customer-related tasks
     * - None (null): Regular users with no management privileges
     */
    public function run(): void
    {
        $this->command->info('Setting management levels based on roles...');
        
        // Get all users
        $users = User::all();
        $count = 0;
        
        foreach ($users as $user) {
            // Assign high management level to Admins
            if ($user->hasRole('Admin')) {
                $user->management_level = User::MANAGEMENT_LEVEL_HIGH;
                $user->save();
                $count++;
                continue;
            }
            
            // Assign middle management level to Employees (skip if already high)
            if ($user->hasRole('Employee') && $user->management_level !== User::MANAGEMENT_LEVEL_HIGH) {
                $user->management_level = User::MANAGEMENT_LEVEL_MIDDLE;
                $user->save();
                $count++;
                continue;
            }
            
            // Assign low management level only to Customers with "supervisor" in their name
            if ($user->hasRole('Customer') && 
                stripos($user->name, 'supervisor') !== false && 
                $user->management_level === null) {
                $user->management_level = User::MANAGEMENT_LEVEL_LOW;
                $user->save();
                $count++;
                continue;
            }
            
            // Ensure all other customers have no management level (null)
            if ($user->hasRole('Customer') && $user->management_level !== null) {
                $user->management_level = null;
                $user->save();
                $count++;
            }
        }
        
        $this->command->info("Updated management levels for {$count} users");
    }
}
