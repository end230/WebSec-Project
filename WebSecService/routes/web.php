<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Web\FeedbackController;
use App\Http\Controllers\Web\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\CustomerServiceController;

// Auth routes
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register')->middleware('throttle:3,5');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login')->middleware('rate.login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Password Reset Routes
Route::get('forgot-password', [UsersController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [UsersController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [UsersController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [UsersController::class, 'resetPassword'])->name('password.update');

// SSL Certificate debug route

Route::get('cert-info', function (Request $request) {
    $certInfo = [
        'SSL_CLIENT_VERIFY' => $request->server('SSL_CLIENT_VERIFY'),
        'SSL_CLIENT_S_DN_Email' => $request->server('SSL_CLIENT_S_DN_Email'),
        'SSL_CLIENT_S_DN_CN' => $request->server('SSL_CLIENT_S_DN_CN'),
        'SSL_CLIENT_S_DN' => $request->server('SSL_CLIENT_S_DN'),
        'SSL_CLIENT_M_SERIAL' => $request->server('SSL_CLIENT_M_SERIAL'),
        'SSL_CLIENT_CERT' => $request->server('SSL_CLIENT_CERT') ? 'Present' : 'Not Present',
        'Auth Status' => Auth::check() ? 'Logged in as: ' . Auth::user()->email : 'Not logged in',
        'All SSL Variables' => collect($_SERVER)->filter(function($value, $key) {
            return strpos($key, 'SSL_') === 0;
        })->toArray()
    ];
    
    return response()->json($certInfo, 200, [], JSON_PRETTY_PRINT);
})->name('cert.info');

// Social login routes with rate limiting
Route::middleware(['throttle:10,1'])->group(function () {
    // Google OAuth Routes
    Route::get('login/google', [UsersController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [UsersController::class, 'handleGoogleCallback']);

    // LinkedIn OAuth Routes
    Route::get('login/linkedin', [UsersController::class, 'redirectToLinkedIn'])->name('login.linkedin');
    Route::get('login/linkedin/callback', [UsersController::class, 'handleLinkedInCallback']);

    // Facebook OAuth Routes
    Route::get('login/facebook', [UsersController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('login/facebook/callback', [UsersController::class, 'handleFacebookCallback']);

    // GitHub OAuth Routes
    Route::get('login/github', [UsersController::class, 'redirectToGithub'])->name('login.github');
    Route::get('login/github/callback', [UsersController::class, 'handleGithubCallback']);
}); 

// User management
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

// SSL Certificate management
Route::middleware(['auth:web'])->group(function () {
    Route::get('users/certificate/{user}', [UsersController::class, 'manageCertificate'])->name('users.certificate');
    Route::post('users/certificate/{user}', [UsersController::class, 'saveCertificate'])->name('users.certificate.save');
});

// Product routes
Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/{product}', [ProductsController::class, 'show'])->name('products.show');

// Fix the middleware configuration for product management routes
Route::middleware(['auth:web'])->group(function () {
    // Use the 'permission' middleware correctly
    Route::middleware(['permission:add_products|edit_products|delete_products'])->group(function () {
        Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
        Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
        Route::delete('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
    });
});

// Order routes
// Specific routes first to prevent route conflicts
Route::get('orders/confirmation/{order}', [OrdersController::class, 'confirmation'])->name('orders.confirmation');
Route::get('orders', [OrdersController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrdersController::class, 'show'])->name('orders.show');

// Customer specific routes
Route::middleware(['auth'])->group(function () {
    Route::post('cart/add/{product}', [OrdersController::class, 'addToCart'])->name('cart.add');
    Route::get('cart', [OrdersController::class, 'cart'])->name('cart');
    Route::delete('cart/remove/{productId}', [OrdersController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('checkout', [OrdersController::class, 'checkout'])->name('checkout');
    Route::post('place-order', [OrdersController::class, 'placeOrder'])->name('orders.place');
});

// Routes requiring specific permissions
Route::middleware(['auth'])->group(function () {
    // Order management routes
    Route::middleware(['permission:manage_orders'])->group(function () {
        Route::patch('orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.update.status');
        Route::get('customers/{user}/add-credits', [OrdersController::class, 'addCreditsForm'])->name('add_credits_form');
        Route::post('customers/{user}/add-credits', [OrdersController::class, 'addCredits'])->name('add_credits');
    });
    
    // Customer management routes
    Route::middleware(['permission:list_customers'])->group(function () {
        Route::get('customers', [UserController::class, 'customers'])->name('users.customers');
        Route::get('customers/{user}/credits', [UserController::class, 'showAddCredits'])->name('users.credits.show');
        Route::post('customers/{user}/credits', [UserController::class, 'addCredits'])->name('users.credits.add');
    });
    
    // Order cancellation routes - now available to anyone with cancel_order permission
    Route::get('/orders/{order}/cancel', [App\Http\Controllers\Web\OrdersController::class, 'showCancelForm'])
        ->middleware(['auth', 'permission:cancel_order'])
        ->name('orders.cancel.form');

    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Web\OrdersController::class, 'cancelOrder'])
        ->middleware(['auth', 'permission:cancel_order'])
        ->name('orders.cancel');
});

// User profile route
Route::get('user/profile/{user?}', [UserController::class, 'profile'])->name('user.profile');

// Admin routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['permission:manage_employees'])->group(function () {
        Route::get('employees/create', [UsersController::class, 'createEmployee'])->name('create_employee');
        Route::post('employees/store', [UsersController::class, 'storeEmployee'])->name('store_employee');
    });
});

// Role management routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['permission:manage_roles'])->group(function () {
        Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RolesController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolesController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RolesController::class, 'destroy'])->name('roles.destroy');
    });
});

// Basic pages
Route::get('/', function (Request $request) {
    // Check for valid SSL certificate
    $clientVerify = $request->server('SSL_CLIENT_VERIFY');
    $clientEmail = $request->server('SSL_CLIENT_S_DN_Email');
    $clientSerial = $request->server('SSL_CLIENT_M_SERIAL');

    if ($clientVerify === 'SUCCESS' && ($clientEmail || $clientSerial)) {
        // The SSLCertificateAuth middleware will handle the authentication
        // We just need to wait a moment for it to complete
        if (!Auth::check()) {
            // Find the user
            $user = null;
            if ($clientEmail) {
                $user = \App\Models\User::where('email', $clientEmail)->first();
            }
            if (!$user && $clientSerial) {
                $user = \App\Models\User::where('certificate_serial', $clientSerial)->first();
            }
            
            // If we found a user, log them in
            if ($user) {
                Auth::login($user);
            }
        }
        
        // If successfully authenticated, redirect to products
        if (Auth::check()) {
            return redirect()->route('products_list');
        }
    }

    // If no valid certificate or authentication failed, show welcome page
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/test-theme', function () {
    return view('test-theme');
});

Route::get('/transcript', function () {
    $transcript = [
        'Mathematics' => 'A',
        'Physics' => 'B+',
        'Chemistry' => 'A-',
        'Biology' => 'B',
        'Computer Science' => 'A+'
    ];
    return view('transcript', ['transcript' => $transcript]);
});

Route::get('/calculator', function () {
    return view('calculator');
});

// Feedback Routes
Route::middleware(['auth', 'permission:view_customer_feedback|respond_to_feedback'])->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/feedback/{feedback}/respond', [FeedbackController::class, 'respond'])->name('feedback.respond');
});

// Add notification route
Route::get('/notifications/mark-as-read', function() {
    Auth::user()->unreadNotifications->markAsRead();
    return redirect()->back()->with('success', 'All notifications marked as read');
})->middleware(['auth'])->name('notifications.markAsRead');

// Admin fix route
Route::get('/fix-admin-permissions', [App\Http\Controllers\Web\UsersController::class, 'fixAdminPermissions'])
    ->middleware(['auth'])->name('fix.admin.permissions');

// Theme preferences route
Route::post('/save-theme-preferences', [App\Http\Controllers\Web\UsersController::class, 'saveThemePreferences'])
    ->middleware(['auth'])->name('save.theme.preferences');
    
// Verify email route
Route::get('verify', [UsersController::class, 'verify'])->name('verify');

// Admin Management Routes
Route::middleware(['auth', 'permission:manage_roles|manage_permissions|assign_admin_role'])->group(function () {
    Route::get('/admin-management', [AdminManagementController::class, 'index'])
        ->name('admin-management.index');
    Route::get('/admin-management/create', [AdminManagementController::class, 'create'])
        ->name('admin-management.create');
    Route::post('/admin-management', [AdminManagementController::class, 'store'])
        ->name('admin-management.store');
    Route::get('/admin-management/{admin}/edit', [AdminManagementController::class, 'edit'])
        ->name('admin-management.edit');
    Route::put('/admin-management/{admin}', [AdminManagementController::class, 'update'])
        ->name('admin-management.update');
});

// Editor Permission Toggle - Only Editors can access this
Route::middleware(['auth', 'role:Editor'])->group(function () {
    Route::post('/admin-management/{admin}/toggle-editor-permissions', [AdminManagementController::class, 'toggleEditorPermissions'])
        ->name('admin-management.toggle-editor-permissions');
});

// Product Comments Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/comments', [App\Http\Controllers\ProductCommentController::class, 'store'])
        ->name('products.comments.store');
});

// Comment Management Routes (for moderation)
Route::middleware(['auth', 'permission:view_customer_feedback'])->group(function () {
    Route::get('/comments', [App\Http\Controllers\ProductCommentController::class, 'index'])
        ->name('comments.index');
    Route::get('/comments/{comment}', [App\Http\Controllers\ProductCommentController::class, 'show'])
        ->name('comments.show');
    Route::patch('/comments/{comment}/approval', [App\Http\Controllers\ProductCommentController::class, 'updateApproval'])
        ->name('comments.approval');
    Route::delete('/comments/{comment}', [App\Http\Controllers\ProductCommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// Customer Service Routes
Route::middleware(['auth', 'permission:view_customer_feedback'])->group(function () {
    Route::get('/customer-service/dashboard', [App\Http\Controllers\CustomerServiceController::class, 'dashboard'])
        ->name('customer-service.dashboard');
    Route::get('/customer-service/cases', [App\Http\Controllers\CustomerServiceController::class, 'index'])
        ->name('customer-service.index');
    Route::get('/customer-service/cases/{case}', [App\Http\Controllers\CustomerServiceController::class, 'show'])
        ->name('customer-service.show');
    Route::post('/customer-service/cases/{case}/assign', [App\Http\Controllers\CustomerServiceController::class, 'assign'])
        ->name('customer-service.assign');
    Route::patch('/customer-service/cases/{case}/status', [App\Http\Controllers\CustomerServiceController::class, 'updateStatus'])
        ->name('customer-service.status');
    Route::patch('/customer-service/cases/{case}/priority', [App\Http\Controllers\CustomerServiceController::class, 'updatePriority'])
        ->name('customer-service.priority');
    Route::post('/customer-service/cases/{case}/comment', [App\Http\Controllers\CustomerServiceController::class, 'addComment'])
        ->name('customer-service.comment');
    Route::post('/customer-service/cases/{case}/note', [App\Http\Controllers\CustomerServiceController::class, 'addInternalNote'])
        ->name('customer-service.note');
    Route::post('/customer-service/cases/{case}/resolve', [App\Http\Controllers\CustomerServiceController::class, 'resolve'])
        ->name('customer-service.resolve');
    Route::get('/customer-service/analytics', [App\Http\Controllers\CustomerServiceController::class, 'analytics'])
        ->name('customer-service.analytics');
});
