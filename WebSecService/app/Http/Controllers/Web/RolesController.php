<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    /**
     * Display a listing of all roles.
     */
    public function index()
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in database.
     */
    public function store(Request $request)
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate role data
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'management_level' => ['nullable', 'string', 'in:low,middle,high'],
        ]);

        // Create role using DB transaction
        DB::beginTransaction();
        try {
            // Create new role
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'management_level' => $request->management_level,
            ]);

            // Assign permissions to role
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Get all permissions with information about which ones are assigned to this role
        $permissions = Permission::all();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in database.
     */
    public function update(Request $request, Role $role)
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate role data
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => ['array'],
            'management_level' => ['nullable', 'string', 'in:low,middle,high'],
        ]);

        // Prevent modifying Admin role if user is not an admin
        if ($role->name === 'Admin' && !auth()->user()->hasRole('Admin')) {
            return redirect()->back()->with('error', 'You cannot modify the Admin role.');
        }

        // Update role using DB transaction
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'management_level' => $request->management_level,
            ]);
            
            // Sync permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified role from database.
     */
    public function destroy(Role $role)
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deleting Admin, Employee or Customer roles
        if (in_array($role->name, ['Admin', 'Employee', 'Customer'])) {
            return redirect()->back()->with('error', 'Cannot delete system roles.');
        }

        try {
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
