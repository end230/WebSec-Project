<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - GreenTea</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Early theme application -->
    <script>
        // Apply theme early to avoid flash of wrong theme
        (function() {
            const darkMode = localStorage.getItem('theme') === 'dark';
            const colorTheme = localStorage.getItem('colorTheme');
            
            if (darkMode) document.documentElement.setAttribute('data-theme', 'dark');
            
            if (colorTheme && colorTheme !== 'default') {
                document.documentElement.setAttribute('data-color-theme', colorTheme);
            }
        })();
    </script>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Heroicons (replacing Bootstrap Icons) -->
    <script src="https://unpkg.com/@heroicons/v1/outline/"></script>
    
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            /* Brand Colors */
            --primary: #688c50;
            --primary-light: #a0c676;
            --primary-dark: #43592d;
            --secondary: #d1ab51;
            --secondary-light: #e7cc8a;
            --secondary-dark: #9c7f33;
            --accent: #5a8a72;
            
            /* UI Colors */
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            
            /* Neutral Colors */
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --black: #000000;
            
            /* Theme Colors */
            --body-bg: #f5f9f2;
            --card-bg: var(--white);
            --text-color: #2c3c25;
            --border-color: #dbe8d1;
            
            /* Shadows */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
            
            /* Container max width */
            --container-max-width: 1200px;
            
            /* Layout Spacing */
            --navbar-height: 64px;
            --section-spacing: 3rem;
            
            /* Typography */
            --font-family: 'Poppins', sans-serif;
            --font-size-xs: 0.75rem;    /* 12px */
            --font-size-sm: 0.875rem;   /* 14px */
            --font-size-base: 1rem;     /* 16px */
            --font-size-lg: 1.125rem;   /* 18px */
            --font-size-xl: 1.25rem;    /* 20px */
            --font-size-2xl: 1.5rem;    /* 24px */
            --font-size-3xl: 1.875rem;  /* 30px */
            --font-size-4xl: 2.25rem;   /* 36px */
            
            /* Border Radius */
            --radius-sm: 0.25rem;
            --radius: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-full: 9999px;
            
            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition: 0.25s ease;
            --transition-slow: 0.35s ease;
            
            /* Z-index layers */
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
            --z-popover: 1060;
            --z-tooltip: 1070;
        }

        /* Dark Theme Variables */
        [data-theme="dark"] {
            --body-bg: #1c2419;
            --card-bg: #2a3326;
            --text-color: #e9f0e6;
            --border-color: #3d4b38;
            
            --gray-100: #212924;
            --gray-200: #343d30;
            --gray-300: #465242;
            --gray-400: #5a6854;
            --gray-500: #6e7c67;
            --gray-600: #8c9985;
            --gray-700: #a9b5a3;
            --gray-800: #c7d1c2;
            --gray-900: #e5ede0;
            
            /* Shadow Adjustments for Dark Mode */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.2);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.25);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* ===== SCROLLBAR STYLES ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--gray-400);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--gray-500);
        }
        
        /* Dark mode scrollbar */
        [data-theme="dark"] ::-webkit-scrollbar-track {
            background-color: var(--gray-800);
        }

        /* Steam animation keyframes */
        @keyframes steamFloat {
            0% {
                opacity: 0;
                transform: translateY(0) scaleX(1);
            }
            15% {
                opacity: 1;
            }
            50% {
                transform: translateY(-10px) scaleX(1.2);
            }
            95% {
                opacity: 0;
            }
            100% {
                transform: translateY(-20px) scaleX(0.8);
                opacity: 0;
            }
        }
        
        .logo-steam {
            opacity: 0;
            transform-origin: bottom center;
            animation: steamFloat 3s infinite ease-out;
        }
    </style>
</head>
<body class="bg-greentea-50 dark:bg-gray-900 text-greentea-900 dark:text-white antialiased">
    @include('layouts.menu')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-16">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-4 rounded-md" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-4 rounded-md" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-4 rounded-md" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        <!-- Notifications Area -->
        @auth
            @if(Auth::user()->can('view_customer_feedback') && isset($feedbackNotifications) && $unreadFeedbackCount > 0)
            <div class="mb-4">
                <div class="relative inline-block">
                    <button class="bg-greentea-500 text-white rounded-md px-3 py-2 flex items-center space-x-2 hover:bg-greentea-600 transition-colors" id="notificationDropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        <span>Notifications</span>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs text-white font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadFeedbackCount }}
                        </span>
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700" id="notificationDropdownMenu">
                        <div class="px-4 py-2 font-medium border-b border-gray-200 dark:border-gray-700">Customer Feedback & Cancellations</div>
                        
                        @foreach($feedbackNotifications as $notification)
                            <a href="{{ $notification->data['url'] }}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                @if($notification->type == 'App\Notifications\OrderCancelled')
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 bg-red-500 rounded-full p-2 mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium">Order #{{ $notification->data['order_id'] }} cancelled</p>
                                            <p class="text-sm">{{ $notification->data['customer_name'] }} - {{ $notification->data['reason'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($notification->data['cancelled_at'])->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @elseif($notification->type == 'App\Notifications\NewFeedback')
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 bg-yellow-500 rounded-full p-2 mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium">New feedback received</p>
                                            <p class="text-sm">{{ $notification->data['customer_name'] }} - Order #{{ $notification->data['order_id'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($notification->data['submitted_at'])->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 text-center py-2">
                            <a href="{{ route('feedback.index') }}" class="text-greentea-600 dark:text-greentea-400 text-sm font-medium hover:underline">
                                View all feedback
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endauth

        @yield('content')
    </main>

    <!-- Theme Toggle Button -->
    <div class="fixed bottom-4 right-4 z-10" id="darkModeToggle">
        <button class="bg-white dark:bg-gray-800 text-greentea-600 dark:text-yellow-400 rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:shadow-xl transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notification dropdown functionality
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationMenu = document.getElementById('notificationDropdownMenu');
            
            if (notificationDropdown && notificationMenu) {
                notificationDropdown.addEventListener('click', function() {
                    notificationMenu.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationDropdown.contains(event.target) && !notificationMenu.contains(event.target)) {
                        notificationMenu.classList.add('hidden');
                    }
                });
            }

            // Theme management system
            const themeManager = {
                getTheme() {
                    return {
                        darkMode: localStorage.getItem('theme') === 'dark',
                        colorTheme: localStorage.getItem('colorTheme') || 'default'
                    };
                },
                
                applyTheme(settings) {
                    if (settings.darkMode) {
                        document.documentElement.classList.add('dark');
                        document.documentElement.setAttribute('data-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.removeAttribute('data-theme');
                    }
                    
                    if (settings.colorTheme && settings.colorTheme !== 'default') {
                        document.documentElement.setAttribute('data-color-theme', settings.colorTheme);
                    } else {
                        document.documentElement.removeAttribute('data-color-theme');
                    }
                    
                    this.updateUIElements(settings);
                },
                
                updateUIElements(settings) {
                    const darkModeToggle = document.getElementById('darkModeToggle');
                    
                    if (darkModeToggle) {
                        const iconElement = darkModeToggle.querySelector('svg');
                        if (settings.darkMode) {
                            iconElement.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />`;
                        } else {
                            iconElement.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />`;
                        }
                    }
                },
                
                toggleDarkMode() {
                    const currentSettings = this.getTheme();
                    const newSettings = {
                        ...currentSettings,
                        darkMode: !currentSettings.darkMode
                    };
                    
                    localStorage.setItem('theme', newSettings.darkMode ? 'dark' : 'light');
                    this.applyTheme(newSettings);
                },
                
                setColorTheme(theme) {
                    const currentSettings = this.getTheme();
                    const newSettings = {
                        ...currentSettings,
                        colorTheme: theme
                    };
                    
                    localStorage.setItem('colorTheme', theme);
                    this.applyTheme(newSettings);
                }
            };
            
            // Initialize theme
            themeManager.applyTheme(themeManager.getTheme());
            
            // Set up dark mode toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', () => {
                    themeManager.toggleDarkMode();
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
