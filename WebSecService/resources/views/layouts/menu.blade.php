<nav class="navbar navbar-expand-lg navbar-light fixed-top animate__animated animate__fadeInDown" style="animation-duration: 0.5s;">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/">
      <div class="brand-icon">
        <i class="bi bi-bag-heart"></i>
      </div>
      <span>Modern Store</span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="/">
            <i class="bi bi-house me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="{{ route('products_list') }}">
            <i class="bi bi-grid me-1"></i> Products
          </a>
        </li>
        @auth
          <!-- Admin Links - Only visible to users with admin_users permission or Editor role -->
          @if(auth()->user()->hasPermissionTo('admin_users') || auth()->user()->hasRole('Editor'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('users') }}">
                <i class="fas fa-users"></i> Manage Users
              </a>
            </li>
          @endif
          
          <!-- Role Management Links - Visible to users with manage_roles_permissions permission -->
          @if(auth()->user()->hasPermissionTo('manage_roles_permissions'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('roles.index') }}">
                <i class="fas fa-user-tag"></i> Manage Roles
              </a>
            </li>
          @endif
          
          <!-- Admin Management Links - Visible to users with manage_roles_permissions or assign_admin_role permission -->
          @if(auth()->user()->hasPermissionTo('manage_roles_permissions') || auth()->user()->hasPermissionTo('assign_admin_role'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('admin-management.index') }}">
                <i class="fas fa-user-shield"></i> Manage Admins
              </a>
            </li>
          @endif
        
          <!-- Employee Links - Only visible to users with view_customer_feedback permission -->
          @if(auth()->user()->hasPermissionTo('view_customer_feedback'))
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="customerServiceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-headset"></i> Customer Service
              </a>
              <ul class="dropdown-menu" aria-labelledby="customerServiceDropdown">
                <li><a class="dropdown-item" href="{{ route('customer-service.dashboard') }}">
                  <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('customer-service.index') }}">
                  <i class="bi bi-ticket-detailed"></i> All Cases
                </a></li>
                <li><a class="dropdown-item" href="{{ route('comments.index') }}">
                  <i class="bi bi-chat-left-text"></i> Product Comments
                </a></li>
                <li><a class="dropdown-item" href="{{ route('customer-service.analytics') }}">
                  <i class="bi bi-graph-up"></i> Analytics
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('feedback.index') }}">
                  <i class="bi bi-envelope"></i> Order Feedback
                </a></li>
              </ul>
            </li>
          @endif

          <!-- Order Management Links - Only visible to users with manage_orders permission -->
          @if(auth()->user()->hasPermissionTo('manage_orders'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('orders.index') }}">
                <i class="fas fa-box"></i> Manage Orders
              </a>
            </li>
          @endif

          <!-- Customer Links - Always visible to logged-in users -->
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center position-relative" href="{{ route('cart') }}">
              <i class="bi bi-cart me-1"></i> Cart
              @if(session()->has('cart') && count(session()->get('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__pulse animate__infinite">
                  {{ count(session()->get('cart')) }}
                </span>
              @endif
            </a>
          </li>
          
          <!-- My Orders - Customer view -->
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="{{ route('orders.index') }}">
              <i class="fas fa-shopping-bag"></i> My Orders
            </a>
          </li>
        @endauth
      </ul>
      <ul class="navbar-nav ms-auto">
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            {{ auth()->user()->name }}
            @if(auth()->user()->hasRole('Editor'))
              <span class="badge bg-info ms-1">Editor</span>
            @elseif(auth()->user()->hasRole('Admin'))
              @php
                // Check if this admin has editor-level permissions
                $hasEditorPermissions = auth()->user()->hasEditorLevelPermissions();
              @endphp
              @if($hasEditorPermissions)
                <span class="badge bg-danger ms-1">Admin</span>
                <span class="badge bg-info ms-1">+Editor Perms</span>
              @else
                <span class="badge bg-danger ms-1">Admin</span>
              @endif
            @elseif(auth()->user()->hasRole('Employee'))
              <span class="badge bg-success ms-1">Employee</span>
            @elseif(auth()->user()->hasRole('Customer Service'))
              <span class="badge bg-warning ms-1">Customer Service</span>
            @endif
          </a>
          <!-- User dropdown menu -->
          <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="userDropdown" style="border-radius: 10px; border: 1px solid var(--border-color); box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                <i class="bi bi-person me-2"></i> Profile
              </a>
            </li>
            @if(auth()->user()->hasRole('Customer'))
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('orders.index') }}">
                <i class="bi bi-bag me-2"></i> My Orders
              </a>
            </li>
            @endif
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('do_logout') }}">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary text-white ms-2 px-3 d-flex align-items-center" href="{{ route('register') }}">
            <i class="bi bi-person-plus me-1"></i> Register
          </a>
        </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
<!-- Add padding to body to account for fixed navbar -->
<div style="padding-top: 60px;"></div>

<style>
/* Modern Navbar Styling */
.navbar {
    height: 60px;
    background: rgba(139, 69, 19, 0.15); /* Soft brown base */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(156, 175, 136, 0.1);
}

.navbar.scrolled {
    background: rgba(139, 69, 19, 0.25); /* Slightly darker brown when scrolled */
    box-shadow: 0 2px 15px rgba(47, 82, 51, 0.1);
}

/* Dark mode overrides */
[data-theme="dark"] .navbar {
    background: rgba(47, 82, 51, 0.85); /* Dark green base */
    border-bottom: 1px solid rgba(156, 175, 136, 0.05);
}

[data-theme="dark"] .navbar.scrolled {
    background: rgba(47, 82, 51, 0.95); /* Darker green when scrolled */
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
}

/* Brand Icon Animation */
.brand-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.brand-icon i {
    font-size: 1.4rem;
    color: var(--theme-primary);
    transition: all 0.3s ease;
}

[data-theme="dark"] .brand-icon i {
    color: rgba(156, 175, 136, 0.9); /* Lighter green in dark mode */
}

.brand-icon:hover {
    transform: scale(1.1);
}

.brand-icon:hover i {
    animation: pulse 1s infinite;
}

/* Navbar Links */
.navbar-nav .nav-link {
    position: relative;
    padding: 0.5rem 1rem;
    color: var(--text-color) !important;
    transition: all 0.3s ease;
    opacity: 0.9;
}

[data-theme="dark"] .navbar-nav .nav-link {
    color: rgba(255, 253, 208, 0.9) !important; /* Light cream color in dark mode */
}

.navbar-nav .nav-link:hover {
    transform: scale(1.05);
    opacity: 1;
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: var(--theme-primary);
    transition: all 0.3s ease;
    transform: translateX(-50%);
    opacity: 0;
}

[data-theme="dark"] .navbar-nav .nav-link::after {
    background: rgba(156, 175, 136, 0.9); /* Lighter green in dark mode */
}

.navbar-nav .nav-link:hover::after,
.navbar-nav .nav-link.active::after {
    width: 100%;
    opacity: 1;
}

/* Dropdown Styling */
.dropdown-menu {
    border: 1px solid rgba(139, 69, 19, 0.1);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 5px 15px rgba(47, 82, 51, 0.08);
    animation: fadeIn 0.3s ease;
}

[data-theme="dark"] .dropdown-menu {
    background: rgba(47, 82, 51, 0.95);
    border: 1px solid rgba(156, 175, 136, 0.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.dropdown-item {
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    color: var(--text-color);
}

[data-theme="dark"] .dropdown-item {
    color: rgba(255, 253, 208, 0.9);
}

.dropdown-item:hover {
    background: rgba(139, 69, 19, 0.05);
    transform: translateX(5px);
}

[data-theme="dark"] .dropdown-item:hover {
    background: rgba(156, 175, 136, 0.1);
}

/* Badge Styling */
.badge {
    transition: all 0.3s ease;
}

[data-theme="dark"] .badge {
    opacity: 0.9;
}

.navbar-nav .nav-link:hover .badge {
    transform: scale(1.1);
}

/* Mobile Responsiveness */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 0.5rem;
        box-shadow: 0 5px 15px rgba(47, 82, 51, 0.08);
        border: 1px solid rgba(139, 69, 19, 0.1);
    }

    [data-theme="dark"] .navbar-collapse {
        background: rgba(47, 82, 51, 0.98);
        border: 1px solid rgba(156, 175, 136, 0.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .navbar-nav .nav-link::after {
        bottom: -2px;
    }
}

/* Divider styling */
.dropdown-divider {
    border-top: 1px solid rgba(139, 69, 19, 0.1);
}

[data-theme="dark"] .dropdown-divider {
    border-top: 1px solid rgba(156, 175, 136, 0.1);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

<script>
// Add scroll effect to navbar
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Add active class to current page link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});
</script>
