<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Editor']);
    }

    /**
     * Display a listing of all admins.
     */
    public function index()
    {
        $admins = User::role('Admin')->get();
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
        ]);

        $user->assignRole('Admin');

        return redirect()->route('admin-management.index')
            ->with('success', 'Admin created successfully.');
    }
}
