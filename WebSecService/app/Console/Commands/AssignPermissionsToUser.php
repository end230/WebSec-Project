<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:assign-to-user {email : The email of the user to assign permissions to} {permissions* : The permissions to assign}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign permissions directly to a user regardless of their role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $permissionNames = $this->argument('permissions');

        // Find the user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        // Check if permissions exist
        $validPermissions = [];
        $invalidPermissions = [];
        
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $validPermissions[] = $permission->name;
            } else {
                $invalidPermissions[] = $permissionName;
            }
        }

        if (!empty($invalidPermissions)) {
            $this->error("The following permissions do not exist: " . implode(', ', $invalidPermissions));
            $this->info("Available permissions: " . implode(', ', Permission::pluck('name')->toArray()));
            
            if (empty($validPermissions)) {
                return 1;
            }
            
            if (!$this->confirm('Do you want to continue with the valid permissions?')) {
                return 1;
            }
        }

        // Assign permissions to user
        foreach ($validPermissions as $permission) {
            $user->givePermissionTo($permission);
            $this->info("Assigned permission '{$permission}' to user {$user->name}.");
        }

        $this->info("Permissions assigned successfully to {$user->name}!");
        return 0;
    }
} 