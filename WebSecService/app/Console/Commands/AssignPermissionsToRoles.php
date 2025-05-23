<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRoles extends Command
{
    protected $signature = 'permissions:assign-to-roles';
    protected $description = 'Assign permissions to appropriate roles';

    public function handle()
    {
        // Get the roles
        $adminRole = Role::findByName('Admin');
        $editorRole = Role::where('name', 'Editor')->first();
        
        if (!$adminRole) {
            $this->error("Admin role not found!");
            return Command::FAILURE;
        }
        
        // Create Editor role if it doesn't exist
        if (!$editorRole) {
            $editorRole = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
            $this->info("Created Editor role");
        }
        
        // Get the permissions
        $manageRolesPermission = Permission::where('name', 'manage_roles_permissions')->first();
        $assignAdminRolePermission = Permission::where('name', 'assign_admin_role')->first();
        
        if (!$manageRolesPermission || !$assignAdminRolePermission) {
            $this->error("Required permissions not found! Run permissions:create-missing first.");
            return Command::FAILURE;
        }
        
        // Assign permissions to Admin role
        if (!$adminRole->hasPermissionTo('manage_roles_permissions')) {
            $adminRole->givePermissionTo('manage_roles_permissions');
            $this->info("Assigned 'manage_roles_permissions' to Admin role");
        }
        
        if (!$adminRole->hasPermissionTo('assign_admin_role')) {
            $adminRole->givePermissionTo('assign_admin_role');
            $this->info("Assigned 'assign_admin_role' to Admin role");
        }
        
        // Assign permissions to Editor role
        if (!$editorRole->hasPermissionTo('manage_roles_permissions')) {
            $editorRole->givePermissionTo('manage_roles_permissions');
            $this->info("Assigned 'manage_roles_permissions' to Editor role");
        }
        
        if (!$editorRole->hasPermissionTo('assign_admin_role')) {
            $editorRole->givePermissionTo('assign_admin_role');
            $this->info("Assigned 'assign_admin_role' to Editor role");
        }
        
        $this->info("Permissions assigned successfully!");
        
        return Command::SUCCESS;
    }
} 