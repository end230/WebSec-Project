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
     * Ensure only users with manage_roles_permissions can access these controller actions
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage_roles_permissions']);
    }

    /**
     * Display a listing of all roles.
     */
    public function index()
    {
        // Remove the individual permission check as we're now using middleware
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        // Remove the individual permission check as we're now using middleware
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in database.
     */
    public function store(Request $request)
    {
        // Remove the individual permission check as we're now using middleware

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
        // Remove the individual permission check as we're now using middleware
        
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
        // Remove the individual permission check as we're now using middleware

        // Validate role data
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => ['array'],
            'management_level' => ['nullable', 'string', 'in:low,middle,high'],
        ]);

        // Only Editor role can modify Admin role (ensured by middleware)
        if ($role->name === 'Admin') {
            // Allow it since the user has the Editor role (checked by middleware)
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
        // Remove the individual permission check as we're now using middleware

        // Prevent deleting Admin, Employee, Customer or Editor roles
        if (in_array($role->name, ['Admin', 'Employee', 'Customer', 'Editor'])) {
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
