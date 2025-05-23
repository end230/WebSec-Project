<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateEditorRole extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the Editor role
        $editorRole = Role::create(['name' => 'Editor']);

        // Ensure the required permissions exist
        $addAdminsPermission = Permission::findOrCreate('add admins');
        $viewAdminsPermission = Permission::findOrCreate('view admins');

        // Assign permissions to the Editor role
        $editorRole->givePermissionTo([
            $addAdminsPermission,
            $viewAdminsPermission
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the Editor role and its associated permissions
        $role = Role::findByName('Editor');
        if ($role) {
            $role->delete();
        }
    }
}
