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
          <!-- Admin Links -->
          @if(auth()->user()->hasPermissionTo('access_admin_panel'))
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('users') }}">
                <i class="fas fa-users"></i> Manage Users
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('roles.index') }}">
                <i class="fas fa-user-tag"></i> Manage Roles
              </a>
            </li>
          @endif

          <!-- Employee Links -->
          @if(auth()->user()->hasPermissionTo('view_customer_feedback'))
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="feedbackDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-comment-alt"></i> Feedback
              </a>
              <ul class="dropdown-menu" aria-labelledby="feedbackDropdown">
                <li><a class="dropdown-item" href="{{ route('feedback.index') }}">View All Feedback</a></li>
              </ul>
            </li>
          @endif

          <!-- Customer Links -->
          @if(auth()->check())
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
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('orders.index') }}">
                <i class="fas fa-box"></i> My Orders
              </a>
            </li>
          @endif
        @endauth
      </ul>
      <ul class="navbar-nav ms-auto">
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            {{ auth()->user()->name }}
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
