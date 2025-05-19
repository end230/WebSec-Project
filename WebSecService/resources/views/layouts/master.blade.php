<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Modern Store</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Early theme application -->
    <script>
        // Apply theme early to avoid flash of wrong theme
        (function() {
            // Check for user's saved theme preferences
            const darkMode = localStorage.getItem('theme') === 'dark';
            const colorTheme = localStorage.getItem('colorTheme');
            
            // Apply dark mode if needed
            if (darkMode) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            
            // Apply color theme if needed
            if (colorTheme && colorTheme !== 'default') {
                document.documentElement.setAttribute('data-color-theme', colorTheme);
            }
        })();
    </script>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Base theme (Light) */
        :root {
            /* Core colors */
            --primary-color: #4a6cf7;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --body-bg: #ffffff;
            --text-color: #212529;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --scrollbar-track: #f1f1f1;
            --scrollbar-thumb: #4a6cf7;
            
            /* Theme specific accent colors */
            --theme-primary: #4a6cf7;
            --theme-secondary: #6c757d;
            --theme-accent: #5c7cfa;
            --theme-gradient-start: #4a6cf7;
            --theme-gradient-end: #6384ff;
        }

        /* Dark mode variables */
        [data-theme="dark"] {
            --body-bg: #1a1d20;
            --text-color: #f8f9fa;
            --border-color: #495057;
            --card-bg: #2a2e33;
            --light-color: #3c4349;
            --dark-color: #f8f9fa;
            --scrollbar-track: #2a2e33;
        }
        
        /* Dark mode specific overrides for better visibility */
        [data-theme="dark"] .btn-outline-primary {
            color: var(--theme-primary);
            border-color: var(--theme-primary);
        }
        
        [data-theme="dark"] .btn-outline-primary:hover {
            background-color: var(--theme-primary);
            color: white !important;
        }
        
        [data-theme="dark"] .btn-light {
            background-color: #f8f9fa;
            color: #212529 !important;
        }
        
        [data-theme="dark"] .text-muted {
            color: #c2c7d0 !important;
        }
        
        [data-theme="dark"] .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="dark"] .table {
            color: var(--text-color);
        }
        
        [data-theme="dark"] .form-control {
            background-color: #2c3237;
            color: #f8f9fa;
            border-color: #495057;
        }
        
        [data-theme="dark"] .form-control:focus {
            background-color: #33383d;
            color: #f8f9fa;
        }
        
        /* Energy Theme (Red) */
        [data-color-theme="energy"] {
            --theme-primary: #e63946;
            --theme-secondary: #ff6b6b;
            --theme-accent: #ff9999;
            --theme-gradient-start: #e63946;
            --theme-gradient-end: #ff6b6b;
            --scrollbar-thumb: #e63946;
        }
        
        /* Calm Theme (Green) */
        [data-color-theme="calm"] {
            --theme-primary: #2a9d8f;
            --theme-secondary: #57cc99;
            --theme-accent: #80ed99;
            --theme-gradient-start: #2a9d8f;
            --theme-gradient-end: #57cc99;
            --scrollbar-thumb: #2a9d8f;
        }
        
        /* Ocean Theme (Blue) */
        [data-color-theme="ocean"] {
            --theme-primary: #0077b6;
            --theme-secondary: #00b4d8;
            --theme-accent: #90e0ef;
            --theme-gradient-start: #0077b6;
            --theme-gradient-end: #00b4d8;
            --scrollbar-thumb: #0077b6;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Cards */
        .card {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
            color: var(--text-color);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-header.bg-primary {
            background-color: var(--theme-primary) !important;
        }
        
        /* Ensure all text in card headers with bg-primary is white */
        .card-header.bg-primary, 
        .card-header.bg-primary h1, 
        .card-header.bg-primary h2, 
        .card-header.bg-primary h3, 
        .card-header.bg-primary h4, 
        .card-header.bg-primary h5, 
        .card-header.bg-primary h6, 
        .card-header.bg-primary p,
        .card-header.bg-primary span {
            color: white !important;
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
            color: white !important; /* Ensure text is visible */
        }

        .btn-primary:hover {
            filter: brightness(110%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradient buttons */
        .btn-gradient {
            background-image: linear-gradient(to right, var(--theme-gradient-start), var(--theme-gradient-end)) !important;
            border: none;
            color: white !important; /* Ensure text is visible */
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .btn-gradient:hover {
            color: white !important;
        }
        
        .btn-gradient:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(to right, var(--theme-gradient-end), var(--theme-gradient-start));
            opacity: 0;
            z-index: -1;
            transition: opacity 0.35s ease;
        }
        
        .btn-gradient:hover:before {
            opacity: 1;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--card-bg) !important;
            transition: background-color 0.3s;
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--text-color);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .navbar-light .navbar-brand {
            color: var(--text-color);
            font-weight: 700;
            transition: color 0.3s;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Form controls */
        .form-control {
            border-radius: 6px;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-color);
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s, color 0.3s;
        }

        .form-control:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.25);
        }

        /* Tables */
        .table {
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        /* Table striped */
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .alert-themed {
            background-color: var(--theme-primary);
            color: white !important; /* Ensure text is visible */
            border-left: 4px solid var(--theme-accent);
        }
        
        .alert-success, .alert-danger, .alert-warning, .alert-info, .alert-primary {
            color: white !important;
        }

        .alert-success {
            background-color: var(--success-color) !important;
        }
        
        .alert-danger {
            background-color: var(--danger-color) !important;
        }
        
        .alert-warning {
            background-color: var(--warning-color) !important;
            color: #212529 !important;
        }
        
        .alert-info {
            background-color: var(--info-color) !important;
        }
        
        .alert-primary {
            background-color: var(--theme-primary) !important;
        }

        /* Animation utility classes */
        .scale-in {
            transform: scale(1.03);
        }

        /* Dropdown menus */
        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: background-color 0.3s, border-color 0.3s;
        }

        .dropdown-item {
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        /* Badge styling */
        .badge {
            color: white !important; /* Ensure text is visible in badges */
        }
        
        .badge-themed {
            background-color: var(--theme-primary) !important;
            color: white !important;
        }
        
        .bg-primary {
            background-color: var(--theme-primary) !important;
            color: white !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
            color: white !important;
        }
        
        .bg-danger {
            background-color: var(--danger-color) !important;
            color: white !important;
        }
        
        .bg-warning {
            background-color: var(--warning-color) !important;
            color: #212529 !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
            color: white !important;
        }
        
        .text-primary {
            color: var(--theme-primary) !important;
        }

        /* White text on colored backgrounds */
        .text-white {
            color: white !important;
        }
        
        /* Dark text for light backgrounds */
        .text-dark {
            color: #212529 !important;
        }

        /* Notification panel styles */
        .notification-panel {
            margin-bottom: 20px;
        }

        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-item {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .notification-content {
            flex: 1;
        }
        
        /* Theme selector */
        .theme-selector {
            display: flex;
            gap: 10px;
        }
        
        .theme-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--border-color);
            transition: transform 0.2s, border-color 0.2s;
        }
        
        .theme-option:hover {
            transform: scale(1.1);
        }
        
        .theme-option.active {
            border-color: var(--theme-primary);
            transform: scale(1.1);
        }
        
        .theme-option.default {
            background-color: #4a6cf7;
        }
        
        .theme-option.energy {
            background-color: #e63946;
        }
        
        .theme-option.calm {
            background-color: #2a9d8f;
        }
        
        .theme-option.ocean {
            background-color: #0077b6;
        }

        /* Container padding */
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: var(--scrollbar-track);
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--scrollbar-thumb);
            border-radius: 10px;
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, background-color 0.3s;
        }

        .dark-mode-toggle:hover {
            transform: translateY(-5px);
        }

        .dark-mode-toggle i {
            font-size: 1.5rem;
            color: var(--text-color);
        }
    </style>
</head>
<body>
    @include('layouts.menu')

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success animate__animated animate__fadeIn" role="alert" id="successAlert">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('successAlert').classList.remove('animate__fadeIn');
                    document.getElementById('successAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('successAlert').style.display = 'none';
                    }, 500);
                }, 3000);
            </script>
        @endif

        @if(session('error'))
            <div class="alert alert-danger animate__animated animate__fadeIn" role="alert" id="errorAlert">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('errorAlert').classList.remove('animate__fadeIn');
                    document.getElementById('errorAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('errorAlert').style.display = 'none';
                    }, 500);
                }, 4000);
            </script>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning animate__animated animate__fadeIn" role="alert" id="warningAlert">
                {{ session('warning') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('warningAlert').classList.remove('animate__fadeIn');
                    document.getElementById('warningAlert').classList.add('animate__fadeOut');
                    setTimeout(function() {
                        document.getElementById('warningAlert').style.display = 'none';
                    }, 500);
                }, 4000);
            </script>
        @endif

        <!-- Notifications Area - Add this where appropriate in your layout -->
        @auth
            @if(Auth::user()->hasPermissionTo('view_customer_feedback') && isset($feedbackNotifications) && $unreadFeedbackCount > 0)
            <div class="notification-panel">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i> Notifications
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadFeedbackCount }}
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                        <li><h6 class="dropdown-header">Customer Feedback & Cancellations</h6></li>
                        
                        @foreach($feedbackNotifications as $notification)
                            <li>
                                <a class="dropdown-item" href="{{ $notification->data['url'] }}">
                                    @if($notification->type == 'App\Notifications\OrderCancelled')
                                        <div class="notification-item">
                                            <div class="notification-icon bg-danger text-white">
                                                <i class="bi bi-x-circle"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="mb-0"><strong>Order #{{ $notification->data['order_id'] }} cancelled</strong></p>
                                                <p class="small text-muted mb-0">{{ $notification->data['customer_name'] }} - {{ $notification->data['reason'] }}</p>
                                                <p class="small text-muted">{{ \Carbon\Carbon::parse($notification->data['cancelled_at'])->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @elseif($notification->type == 'App\Notifications\NewFeedback')
                                        <div class="notification-item">
                                            <div class="notification-icon bg-warning text-dark">
                                                <i class="bi bi-chat-left-text"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="mb-0"><strong>New feedback received</strong></p>
                                                <p class="small text-muted mb-0">{{ $notification->data['customer_name'] }} - Order #{{ $notification->data['order_id'] }}</p>
                                                <p class="small text-muted">{{ \Carbon\Carbon::parse($notification->data['submitted_at'])->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center">
                            <a class="dropdown-item" href="{{ route('feedback.index') }}">
                                View all feedback
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        @endauth

        <div data-aos="fade-up" data-aos-duration="800">
            @yield('content')
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <div class="dark-mode-toggle animate__animated animate__fadeIn" id="darkModeToggle">
        <i class="bi bi-sun"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            once: true,
            offset: 100,
        });

        // Function to handle item removal with animation
        function removeWithAnimation(element, animation, callback) {
            element.classList.add(animation);

            element.addEventListener('animationend', function() {
                if (callback) callback();
                element.remove();
            });
        }

        // Theme management system
        document.addEventListener('DOMContentLoaded', function() {
            // Setup theme manager
            const themeManager = {
                // Get current theme settings
                getTheme() {
                    return {
                        darkMode: localStorage.getItem('theme') === 'dark',
                        colorTheme: localStorage.getItem('colorTheme') || 'default'
                    };
                },
                
                // Apply theme settings to DOM
                applyTheme(settings) {
                    // Apply dark/light mode
                    if (settings.darkMode) {
                        document.documentElement.setAttribute('data-theme', 'dark');
                    } else {
                        document.documentElement.removeAttribute('data-theme');
                    }
                    
                    // Apply color theme
                    if (settings.colorTheme && settings.colorTheme !== 'default') {
                        document.documentElement.setAttribute('data-color-theme', settings.colorTheme);
                    } else {
                        document.documentElement.removeAttribute('data-color-theme');
                    }
                    
                    // Update UI elements
                    this.updateUIElements(settings);
                },
                
                // Update UI elements to match current theme
                updateUIElements(settings) {
                    // Update dark mode toggle
                    const darkModeToggle = document.getElementById('darkModeToggle');
                    if (darkModeToggle) {
                        const icon = darkModeToggle.querySelector('i');
                        if (settings.darkMode) {
                            icon.classList.replace('bi-sun', 'bi-moon-stars');
                        } else {
                            icon.classList.replace('bi-moon-stars', 'bi-sun');
                        }
                    }
                    
                    // Update dark mode switch if it exists
                    const darkModeSwitch = document.getElementById('darkModeSwitch');
                    if (darkModeSwitch) {
                        darkModeSwitch.checked = settings.darkMode;
                    }
                    
                    // Update theme options
                    const themeOptions = document.querySelectorAll('.theme-option');
                    themeOptions.forEach(option => {
                        const themeName = option.getAttribute('data-theme');
                        option.classList.toggle('active', themeName === settings.colorTheme);
                    });
                },
                
                // Save settings to localStorage and database
                saveTheme(settings) {
                    // Save to localStorage for immediate use
                    if (settings.darkMode) {
                        localStorage.setItem('theme', 'dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                    }
                    
                    if (settings.colorTheme && settings.colorTheme !== 'default') {
                        localStorage.setItem('colorTheme', settings.colorTheme);
                    } else {
                        localStorage.removeItem('colorTheme');
                    }
                    
                    // Save to database if user is logged in
                    @auth
                    fetch('{{ route('save.theme.preferences') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            theme_dark_mode: settings.darkMode,
                            theme_color: settings.colorTheme || 'default'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Theme preferences saved to database');
                        }
                    })
                    .catch(error => {
                        console.error('Error saving theme preferences:', error);
                    });
                    @endauth
                },
                
                // Toggle dark mode
                toggleDarkMode() {
                    const settings = this.getTheme();
                    settings.darkMode = !settings.darkMode;
                    this.saveTheme(settings);
                    this.applyTheme(settings);
                },
                
                // Set color theme
                setColorTheme(themeName) {
                    const settings = this.getTheme();
                    settings.colorTheme = themeName;
                    this.saveTheme(settings);
                    this.applyTheme(settings);
                },
                
                // Initialize theme from saved preferences or database
                init() {
                    // Use user's database preferences if available
                    @auth
                    const userDarkMode = {{ Auth::user()->theme_dark_mode ? 'true' : 'false' }};
                    const userColorTheme = '{{ Auth::user()->theme_color }}';
                    
                    // Set them in localStorage so they're available on next load
                    if (userDarkMode) {
                        localStorage.setItem('theme', 'dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                    }
                    
                    if (userColorTheme && userColorTheme !== 'default') {
                        localStorage.setItem('colorTheme', userColorTheme);
                    } else {
                        localStorage.removeItem('colorTheme');
                    }
                    
                    this.applyTheme({
                        darkMode: userDarkMode,
                        colorTheme: userColorTheme
                    });
                    @else
                    // Check for saved preference or use system preference
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const settings = this.getTheme();
                    
                    // If no saved preference, use system preference
                    if (!localStorage.getItem('theme') && prefersDark) {
                        settings.darkMode = true;
                    }
                    
                    this.applyTheme(settings);
                    @endauth
                    
                    // Set up event listeners
                    this.setupEventListeners();
                },
                
                // Set up event listeners
                setupEventListeners() {
                    // Dark mode toggle button
                    const darkModeToggle = document.getElementById('darkModeToggle');
                    if (darkModeToggle) {
                        darkModeToggle.addEventListener('click', () => this.toggleDarkMode());
                    }
                    
                    // Dark mode switch in profile page
                    const darkModeSwitch = document.getElementById('darkModeSwitch');
                    if (darkModeSwitch) {
                        darkModeSwitch.addEventListener('change', () => {
                            const settings = this.getTheme();
                            settings.darkMode = darkModeSwitch.checked;
                            this.saveTheme(settings);
                            this.applyTheme(settings);
                        });
                    }
                    
                    // Theme color options
                    const themeOptions = document.querySelectorAll('.theme-option');
                    themeOptions.forEach(option => {
                        option.addEventListener('click', () => {
                            const themeName = option.getAttribute('data-theme');
                            this.setColorTheme(themeName);
                        });
                    });
                    
                    // Apply theme button
                    const applyThemeBtn = document.querySelector('.btn-gradient');
                    if (applyThemeBtn && applyThemeBtn.closest('.card-body') && applyThemeBtn.closest('.card-body').querySelector('.theme-selector')) {
                        applyThemeBtn.addEventListener('click', () => {
                            const activeTheme = document.querySelector('.theme-option.active');
                            if (activeTheme) {
                                const themeName = activeTheme.getAttribute('data-theme');
                                this.setColorTheme(themeName);
                                
                                // Show success message
                                const alert = document.createElement('div');
                                alert.className = 'alert alert-success animate__animated animate__fadeIn mt-3';
                                alert.textContent = 'Theme applied successfully!';
                                applyThemeBtn.parentNode.appendChild(alert);
                                
                                // Remove alert after 3 seconds
                                setTimeout(() => {
                                    alert.classList.remove('animate__fadeIn');
                                    alert.classList.add('animate__fadeOut');
                                    setTimeout(() => alert.remove(), 500);
                                }, 3000);
                            }
                        });
                    }
                }
            };
            
            // Initialize theme manager
            themeManager.init();

            // Handle cart animations
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => {
                item.classList.add('animate__animated', 'animate__fadeIn');

                // Find remove buttons in cart
                const removeBtn = item.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        // Animate item removal
                        item.classList.remove('animate__fadeIn');
                        item.classList.add('animate__fadeOutRight');

                        item.addEventListener('animationend', function() {
                            form.submit();
                        });
                    });
                }
            });

            // Add hover animations to product cards
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('scale-in');
                });

                card.addEventListener('mouseleave', function() {
                    this.classList.remove('scale-in');
                });
            });
        });
    </script>
</body>
</html>
