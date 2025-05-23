<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:list {--role= : Filter permissions by role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available permissions in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roleFilter = $this->option('role');

        if ($roleFilter) {
            $role = Role::where('name', $roleFilter)->first();
            if (!$role) {
                $this->error("Role '{$roleFilter}' not found.");
                $this->info("Available roles: " . implode(', ', Role::pluck('name')->toArray()));
                return 1;
            }

            $permissions = $role->permissions()->get();
            $this->info("Permissions for role '{$role->name}':");
        } else {
            $permissions = Permission::all();
            $this->info("All available permissions:");
        }

        // Group permissions by category based on naming convention
        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('_', $permission->name);
            $category = count($parts) > 1 ? $parts[0] : 'general';
            
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            
            $grouped[$category][] = $permission->name;
        }

        // Display grouped permissions
        foreach ($grouped as $category => $perms) {
            $this->line("\n<fg=yellow;options=bold>" . strtoupper($category) . " Permissions:</>");
            sort($perms);
            foreach ($perms as $perm) {
                $this->line(" - " . $perm);
            }
        }

        // Display summary
        $this->newLine();
        $this->info("Total permissions: " . count($permissions));
        
        return 0;
    }
} 