<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleManagementLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Assigning management levels to roles...');
        
        // Get the Admin role and assign high level
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->management_level = User::MANAGEMENT_LEVEL_HIGH;
            $adminRole->save();
            $this->command->info("Assigned HIGH management level to Admin role");
        } else {
            $this->command->warn("Admin role not found");
        }
        
        // Get the Employee role and assign middle level
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $employeeRole->management_level = User::MANAGEMENT_LEVEL_MIDDLE;
            $employeeRole->save();
            $this->command->info("Assigned MIDDLE management level to Employee role");
        } else {
            $this->command->warn("Employee role not found");
        }
        
        // Get any other roles with specific naming patterns and assign appropriate levels
        // For example, roles with "supervisor" in the name get low level
        $supervisorRoles = Role::where('name', 'like', '%supervisor%')
            ->whereNull('management_level')
            ->get();
            
        foreach ($supervisorRoles as $role) {
            $role->management_level = User::MANAGEMENT_LEVEL_LOW;
            $role->save();
            $this->command->info("Assigned LOW management level to {$role->name} role");
        }
        
        $this->command->info('Role management levels assigned successfully!');
    }
}
