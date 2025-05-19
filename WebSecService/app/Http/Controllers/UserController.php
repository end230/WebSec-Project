<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // If using "public function createEmployee(Role $role)" 
    // make sure your route includes a {role} parameter 
    // or remove the Role $role parameter if it's not needed.

    /**
     * Display a listing of customers.
     */
    public function customers()
    {
        // Get all users with Customer role
        $customers = User::role('Customer')->get();

        return view('users.customers', compact('customers'));
    }

    /**
     * Show user profile
     */
    public function profile(User $user = null)
    {
        // If no user ID provided or user not found, show current user's profile
        if (!$user || $user->id === null) {
            $user = Auth::user();

            // Redirect if not logged in
            if (!$user) {
                return redirect()->route('login');
            }
        }

        // Get permissions for display
        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    /**
     * Show form to add credits to a user
     */
    public function showAddCredits(User $user)
    {
        return view('users.add_credits', compact('user'));
    }

    /**
     * Add credits to a user
     */
    public function addCredits(Request $request, User $user)
    {
        // Check if current user has necessary permission
        if (!Auth::user()->hasPermissionTo('manage_orders')) {
            abort(403, 'Unauthorized action. You need manage_orders permission.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->input('amount');

        // Additional check to ensure positive value
        if ($amount <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Credit amount must be positive.');
        }

        $user->credits += $amount;
        $user->save();

        return redirect()->route('users.customers')
            ->with('success', "Successfully added {$amount} credits to {$user->name}'s account.");
    }

    /**
     * Show form to create a new employee
     */
    public function createEmployee()
    {
        return view('users.create_employee');
    }

    /**
     * Store a new employee
     */
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the Employee role
        $user->assignRole('Employee');

        return redirect()->route('users')
            ->with('success', 'Employee created successfully.');
    }
}
