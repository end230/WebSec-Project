<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB as DBFacade; // Fix DB import with an alias to prevent naming conflict
use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // Add this import for Str class
use Illuminate\Support\Facades\Schema; // Add missing import for Schema
use Illuminate\Support\Facades\Log; // Add missing import for Log

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if (!auth()->user()->hasPermissionTo('show_users')) {
            abort(403, 'You do not have permission to view users.');
        }
        
        $query = User::select('*');
        $query->when($request->keywords,
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'credits' => 0,
            'management_level' => null, // Customers have no management level
        ]);

        $user->assignRole('Customer');

        Auth::login($user);

        return redirect()->route('home');
    }

    public function createEmployee(Request $request) {
        // Check if user has permission to create employees
        if (!Auth::user()->hasPermissionTo('create_employee')) {
            abort(403, 'Unauthorized action. You need create_employee permission.');
        }

        // Check management level - only high-level managers can create employees
        if (!Auth::user()->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH) && 
            !Auth::user()->hasPermissionTo('assign_management_level')) {
            abort(403, 'Unauthorized action. You need high management level.');
        }

        $roles = Role::where('name', '!=', 'Customer')->get();
        
        return view('users.create-employee', [
            'roles' => $roles,
            'managementLevels' => [
                User::MANAGEMENT_LEVEL_LOW => 'Low (Customer tasks only)',
                User::MANAGEMENT_LEVEL_MIDDLE => 'Middle (Customer & low-level tasks)',
                User::MANAGEMENT_LEVEL_HIGH => 'High (Full system access)',
            ]
        ]);
    }

    public function saveEmployee(Request $request) {
        // Check if user has permission to create employees
        if (!Auth::user()->hasPermissionTo('create_employee')) {
            abort(403, 'Unauthorized action. You need create_employee permission.');
        }

        // Check management level - only high-level managers can create employees
        if (!Auth::user()->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH) && 
            !Auth::user()->hasPermissionTo('assign_management_level')) {
            abort(403, 'Unauthorized action. You need high management level.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'management_level' => 'nullable|string|in:low,middle,high',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'credits' => 0,
            'management_level' => $request->management_level,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.list')
            ->with('success', 'Employee created successfully.');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {

    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {

    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, ?User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(403);
        }

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

    public function edit(Request $request, ?User $user = null) {
        $user = $user ?? auth()->user();
        
        // Fixed permission check - using edit_users instead of show_users
        if(auth()->id() != $user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(403);
        }

        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {
        // Validate basic input
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(403); // Fixed permission check
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {
            // Prevent removing Admin role from self or last admin
            if (auth()->id() == $user->id && $user->hasRole('Admin')) {
                $hasAdminRole = false;
                foreach ($request->roles ?? [] as $role) {
                    if ($role === 'Admin') {
                        $hasAdminRole = true;
                        break;
                    }
                }
                
                if (!$hasAdminRole) {
                    return redirect()->route('users_edit', $user->id)
                        ->with('error', 'You cannot remove your own Admin role.');
                }
            }
            
            // Check if this is the last admin
            if ($user->hasRole('Admin') && !in_array('Admin', $request->roles ?? [])) {
                $adminCount = User::role('Admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->route('users_edit', $user->id)
                        ->with('error', 'Cannot remove Admin role from the only admin account.');
                }
            }

            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);

            Artisan::call('cache:clear');
        }

        return redirect(route('profile', ['user'=>$user->id]))->with('success', 'User updated successfully');
    }

    public function delete(Request $request, User $user) {
        if(!auth()->user()->hasPermissionTo('delete_users')) abort(403);
        
        // Check if trying to delete self
        if (auth()->id() == $user->id) {
            return redirect()->route('users')->with('error', 'You cannot delete your own account.');
        }
        
        // Check if this is the only admin
        if ($user->hasRole('Admin')) {
            $adminCount = User::role('Admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users')->with('error', 'Cannot delete the only admin account.');
            }
        }

        // Safely delete the user
        $user->delete();
        
        return redirect()->route('users')->with('success', 'User deleted successfully');
    }        

    public function savePassword(Request $request, User $user) {
        // If user is changing their own password
        if(auth()->id() == $user?->id) {
            $this->validate($request, [
                'old_password' => ['required'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                // Don't log them out, just show an error
                return redirect()->back()->withErrors('Current password is incorrect.');
            }
        }
        // If admin is changing someone else's password
        else if(auth()->user()->hasPermissionTo('admin_users')) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {
            abort(403);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]))->with('success', 'Password updated successfully');
    }

    public function editPassword(Request $request, ?User $user = null) {
        $user = $user??auth()->user();
        
        // Fixed permission check
        if(auth()->id() != $user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users') && 
               !auth()->user()->hasPermissionTo('admin_users')) {
                abort(403);
            }
        }
        
        return view('users.edit_password', compact('user'));
    }

    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction(); // Use the aliased DB facade here
                try {
                    $newUser = new User();
                    $newUser->name = $googleUser->name;
                    $newUser->email = $googleUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if google_id column exists before using it
                    if (Schema::hasColumn('users', 'google_id')) {
                        $newUser->google_id = $googleUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since Google verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit(); // Use the aliased DB facade here
                } catch (\Exception $e) {
                    DBFacade::rollBack(); // Use the aliased DB facade here
                    Log::error('Failed to create user from Google: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Google authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function redirectToLinkedIn() {
        return Socialite::driver('linkedin')->redirect();
    }

    public function handleLinkedInCallback() {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $linkedinUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction();
                try {
                    $newUser = new User();
                    $newUser->name = $linkedinUser->name;
                    $newUser->email = $linkedinUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if linkedin_id column exists before using it
                    if (Schema::hasColumn('users', 'linkedin_id')) {
                        $newUser->linkedin_id = $linkedinUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since LinkedIn verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit();
                } catch (\Exception $e) {
                    DBFacade::rollBack();
                    Log::error('Failed to create user from LinkedIn: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('LinkedIn authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback() {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $facebookUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction();
                try {
                    $newUser = new User();
                    $newUser->name = $facebookUser->name;
                    $newUser->email = $facebookUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if facebook_id column exists before using it
                    if (Schema::hasColumn('users', 'facebook_id')) {
                        $newUser->facebook_id = $facebookUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since Facebook verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit();
                } catch (\Exception $e) {
                    DBFacade::rollBack();
                    Log::error('Failed to create user from Facebook: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Facebook authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

    /**
     * Fix admin permissions
     */
    public function fixAdminPermissions(Request $request)
    {
        // Only allow authenticated users
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();

        try {
            // Begin transaction to ensure all changes are applied atomically
            DBFacade::beginTransaction();

            // Clear cache to ensure fresh permission data
            Artisan::call('cache:clear');
            Artisan::call('permission:cache-reset');

            // Make sure the Admin role has all permissions
            $adminRole = Role::where('name', 'Admin')->first();
            
            if (!$adminRole) {
                // If Admin role doesn't exist, create it
                $adminRole = Role::create(['name' => 'Admin']);
            }
            
            // Get all permissions
            $allPermissions = Permission::all();
            
            // Assign all permissions to Admin role
            $adminRole->syncPermissions($allPermissions);
            
            // Make sure the current user has the Admin role
            if (!$user->hasRole('Admin')) {
                $user->assignRole('Admin');
            } else {
                // Force refresh user's permissions
                $user->syncRoles(['Admin']);
            }
            
            // Save changes and commit transaction
            DBFacade::commit();
            
            return redirect()->route('users')->with('success', 'Admin permissions have been fixed. You now have full administrative access.');
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DBFacade::rollBack();
            Log::error('Failed to fix admin permissions: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fix permissions: ' . $e->getMessage());
        }
    }
    
    /**
     * Save theme preferences for the user
     */
    public function saveThemePreferences(Request $request)
    {
        // Make sure the user is logged in
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Validate the request
        $request->validate([
            'theme_dark_mode' => 'required|boolean',
            'theme_color' => 'required|string|in:default,energy,calm,ocean',
        ]);
        
        // Get the current user
        $user = Auth::user();
        
        // Update the theme preferences
        $user->theme_dark_mode = $request->theme_dark_mode;
        $user->theme_color = $request->theme_color;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Theme preferences saved successfully',
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check if user has permission to update users
        if (!Auth::user()->hasPermissionTo('edit_user')) {
            abort(403, 'Unauthorized action. You need edit_user permission.');
        }

        // Check management level for role and management level changes
        if (($request->has('role') || $request->has('management_level')) && 
            !Auth::user()->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH) && 
            !Auth::user()->hasPermissionTo('assign_management_level')) {
            abort(403, 'Unauthorized action. You need high management level.');
        }

        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Only validate email if it's changed
        if ($request->email !== $user->email) {
            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        // Password is optional for updates
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        if (Auth::user()->hasPermissionTo('assign_management_level')) {
            $rules['role'] = 'nullable|string|exists:roles,name';
            $rules['management_level'] = 'nullable|string|in:low,middle,high';
        }

        $request->validate($rules);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update role and management level if authorized
        if (Auth::user()->hasPermissionTo('assign_management_level')) {
            // If user is being set as Customer, remove management level
            if ($request->filled('role') && $request->role === 'Customer') {
                $user->management_level = null;
            } else if ($request->filled('management_level')) {
                $user->management_level = $request->management_level;
            }

            // Update role if provided
            if ($request->filled('role')) {
                // Remove existing roles and assign the new one
                $user->syncRoles([$request->role]);
            }
        }

        $user->save();

        return redirect()->route('users.edit', $user->id)
            ->with('success', 'User updated successfully.');
    }
}
