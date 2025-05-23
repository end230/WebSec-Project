<nav class="navbar navbar-expand-lg navbar-light fixed-top animate__animated animate__fadeInDown">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/">
      <i class="bi bi-bag-heart me-2" style="font-size: 1.4rem; color: var(--primary-color);"></i>
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
          
          <!-- Role Management Links - Visible to users with manage_roles permission -->
          @if(auth()->user()->hasPermissionTo('manage_roles'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('roles.index') }}">
                <i class="fas fa-user-tag"></i> Manage Roles
              </a>
            </li>
          @endif
          
          <!-- Admin Management Links - Visible to users with manage_roles or assign_admin_role permission -->
          @if(auth()->user()->hasPermissionTo('manage_roles') || auth()->user()->hasPermissionTo('manage_permissions') || auth()->user()->hasPermissionTo('assign_admin_role'))
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
<div style="padding-top: 70px;"></div>
