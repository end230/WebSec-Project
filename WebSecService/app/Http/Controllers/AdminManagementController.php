<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage_roles|manage_permissions|assign_admin_role']);
    }

    /**
     * Display a listing of all admins.
     */
    public function index()
    {
        $admins = User::role('Admin')->paginate(10);
        
        // Check which admins have Editor permissions
        $editorRole = Role::findByName('Editor');
        $editorPermissions = $editorRole->permissions->pluck('name')->toArray();
        
        // Add a flag to each admin indicating if they have Editor permissions
        $admins->each(function ($admin) use ($editorPermissions) {
            $admin->hasEditorPermissions = $admin->hasEditorLevelPermissions();
        });
        
        return view('admin-management.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('admin-management.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'management_level' => User::MANAGEMENT_LEVEL_HIGH,
        ]);

        $user->assignRole('Admin');

        return redirect()->route('admin-management.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Show the form for editing an admin.
     */
    public function edit(User $admin)
    {
        // Ensure the user is actually an admin
        if (!$admin->hasRole('Admin')) {
            abort(404, 'User is not an admin.');
        }

        // Refresh admin to get latest permissions
        $admin->refresh();

        // Get Editor role permissions to check if admin has them
        $editorRole = Role::findByName('Editor');
        $editorPermissions = $editorRole->permissions->pluck('name')->toArray();
        
        // Check if admin has Editor permissions using the model method
        $hasEditorPermissions = $admin->hasEditorLevelPermissions();

        return view('admin-management.edit', compact('admin', 'hasEditorPermissions', 'editorPermissions'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, User $admin)
    {
        // Ensure the user is actually an admin
        if (!$admin->hasRole('Admin')) {
            abort(404, 'User is not an admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('admin-management.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Toggle Editor permissions for an admin (only accessible by Editors)
     */
    public function toggleEditorPermissions(Request $request, User $admin)
    {
        // Ensure only actual Editors (with the Editor role) can toggle permissions
        if (!Auth::user()->hasRole('Editor')) {
            abort(403, 'Only Editors can grant or remove editor permissions.');
        }

        // Ensure the user is actually an admin
        if (!$admin->hasRole('Admin')) {
            abort(404, 'User is not an admin.');
        }

        // Refresh admin to get latest permissions
        $admin->refresh();

        // Get Editor role permissions
        $editorRole = Role::findByName('Editor');
        $editorPermissions = $editorRole->permissions->pluck('name')->toArray();

        $grantEditorPermissions = $request->boolean('grant_editor_permissions');

        if ($grantEditorPermissions) {
            // Grant Editor permissions (but not the role)
            foreach ($editorPermissions as $permission) {
                if (!$admin->hasPermissionTo($permission)) {
                    $admin->givePermissionTo($permission);
                }
            }
            // Force refresh permissions cache
            $admin->refresh();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $message = 'Editor permissions granted to ' . $admin->name;
        } else {
            // Remove Editor permissions (keep only Admin role permissions)
            $adminRole = Role::findByName('Admin');
            $adminOnlyPermissions = $adminRole->permissions->pluck('name')->toArray();
            
            // Sync permissions to only Admin role permissions
            $admin->syncPermissions($adminOnlyPermissions);
            // Force refresh permissions cache
            $admin->refresh();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $message = 'Editor permissions removed from ' . $admin->name;
        }

        // Redirect back to previous page or admin management index
        $redirectUrl = $request->header('referer');
        if (str_contains($redirectUrl, '/edit')) {
            return redirect()->route('admin-management.edit', $admin)->with('success', $message);
        } else {
            return redirect()->route('admin-management.index')->with('success', $message);
        }
    }
}
