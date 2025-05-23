<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class CreateMissingPermissions extends Command
{
    protected $signature = 'permissions:create-missing';
    protected $description = 'Create missing permissions used in the application';

    public function handle()
    {
        $missingPermissions = [
            'manage_roles_permissions',
            'assign_admin_role'
        ];

        $created = 0;
        foreach ($missingPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
                $this->info("Created permission: {$permission}");
                $created++;
            } else {
                $this->line("Permission already exists: {$permission}");
            }
        }

        $this->info("Created {$created} missing permissions.");
        
        return Command::SUCCESS;
    }
} 