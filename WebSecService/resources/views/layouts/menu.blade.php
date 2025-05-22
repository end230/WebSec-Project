<!-- Modern Navigation Bar -->
<header class="fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-md z-50 h-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between">
        <!-- Brand Logo -->
        <a href="/" class="flex items-center gap-2">
            <svg width="32" height="32" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                <!-- Cup Base -->
                <path d="M30 40 Q30 20 60 20 Q90 20 90 40 L90 80 Q90 100 60 100 Q30 100 30 80 Z" 
                      fill="white" stroke="currentColor" stroke-width="4" class="text-greentea-500"/>
                
                <!-- Cup Handle -->
                <path d="M90 50 Q110 50 110 65 Q110 80 90 80" 
                      fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="text-greentea-500"/>
                
                <!-- Tea Leaf -->
                <path class="text-greentea-500 fill-current" d="M60 40 Q70 30 65 50 Q80 40 70 60 Q85 55 70 70 Q80 80 60 75 Q50 85 50 70 Q35 75 45 60 Q30 60 45 50 Q35 40 50 45 Q45 30 60 40"/>
                
                <!-- Steam Animation -->
                <path class="logo-steam text-greentea-500 stroke-current" d="M50 15 Q55 10 52 5" 
                      fill="none" stroke-width="2" stroke-linecap="round"/>
                <path class="logo-steam text-greentea-500 stroke-current" d="M60 15 Q65 5 62 0" 
                      fill="none" stroke-width="2" stroke-linecap="round" 
                      style="animation-delay: 0.5s"/>
                <path class="logo-steam text-greentea-500 stroke-current" d="M70 15 Q75 10 72 5" 
                      fill="none" stroke-width="2" stroke-linecap="round" 
                      style="animation-delay: 1s"/>
            </svg>
            <span class="font-bold text-xl text-greentea-500">Green<span class="font-light text-greentea-400">Tea</span></span>
        </a>

        <!-- Mobile Toggle -->
        <button class="lg:hidden text-greentea-500 hover:text-greentea-600 focus:outline-none border border-greentea-200 rounded p-2" id="mobile-menu-button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Navigation Items -->
        <nav class="hidden lg:flex flex-col lg:flex-row absolute lg:relative top-16 lg:top-0 left-0 right-0 bg-white dark:bg-gray-800 lg:bg-transparent shadow-md lg:shadow-none lg:flex z-40 py-4 lg:py-0 border-t lg:border-t-0 border-greentea-100 dark:border-gray-700" id="main-navigation">
            <ul class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-6 px-4 lg:px-0">
                <li>
                    <a class="flex items-center space-x-2 py-2 px-3 rounded-md {{ request()->is('/') ? 'bg-greentea-500 text-white' : 'text-greentea-700 hover:bg-greentea-50 dark:text-greentea-300 dark:hover:bg-gray-700' }}" href="/">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span>Home</span>
                    </a>
                </li>

                <li>
                    <a class="flex items-center space-x-2 py-2 px-3 rounded-md {{ request()->routeIs('products_list') ? 'bg-greentea-500 text-white' : 'text-greentea-700 hover:bg-greentea-50 dark:text-greentea-300 dark:hover:bg-gray-700' }}" href="{{ route('products_list') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        <span>Products</span>
                    </a>
                </li>

                @auth
                    @role('Customer')
                        <li class="relative">
                            <a class="flex items-center space-x-2 py-2 px-3 rounded-md {{ request()->routeIs('cart') ? 'bg-greentea-500 text-white' : 'text-greentea-700 hover:bg-greentea-50 dark:text-greentea-300 dark:hover:bg-gray-700' }}" href="{{ route('cart') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                <span>Cart</span>
                                @if(session()->has('cart') && count(session('cart')) > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-xs text-white font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        
                        <li>
                            <a class="flex items-center space-x-2 py-2 px-3 rounded-md {{ request()->routeIs('orders.*') ? 'bg-greentea-500 text-white' : 'text-greentea-700 hover:bg-greentea-50 dark:text-greentea-300 dark:hover:bg-gray-700' }}" href="{{ route('orders.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                </svg>
                                <span>My Orders</span>
                            </a>
                        </li>
                    @endrole
                    
                    @role('Admin|Staff')
                        <li class="relative group">
                            <button class="flex items-center space-x-2 py-2 px-3 rounded-md text-greentea-700 hover:bg-greentea-50 dark:text-greentea-300 dark:hover:bg-gray-700 w-full lg:w-auto text-left" id="management-dropdown-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                <span>Management</span>
                                <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="hidden absolute left-0 lg:right-0 lg:left-auto z-10 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" id="management-dropdown-menu">
                                <div class="py-1">
                                    @can('view_orders')
                                        <a href="{{ route('orders.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Orders</span>
                                        </a>
                                    @endcan
                                    
                                    @can('view_products')
                                        <a href="{{ route('products_list') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                            <span>Products</span>
                                        </a>
                                    @endcan
                                    
                                    @can('view_customer_feedback')
                                        <a href="{{ route('feedback.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Customer Feedback</span>
                                        </a>
                                    @endcan
                                    
                                    @can('view_users')
                                        <a href="{{ route('users') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                                            </svg>
                                            <span>Users</span>
                                        </a>
                                    @endcan
                                    
                                    @can('view_reports')
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        <a href="#reports-sales" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                            </svg>
                                            <span>Sales Reports</span>
                                        </a>
                                        <a href="#reports-inventory" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Inventory Reports</span>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endrole
                @endauth
            </ul>
        </nav>

        <!-- User Actions -->
        <div class="flex items-center">
            @guest
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center space-x-1 border border-greentea-500 text-greentea-500 px-3 py-1.5 rounded-md hover:bg-greentea-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:flex items-center space-x-1 bg-greentea-500 text-white px-3 py-1.5 rounded-md hover:bg-greentea-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                        <span>Register</span>
                    </a>
                    <div class="relative sm:hidden">
                        <button class="flex items-center justify-center border border-greentea-500 text-greentea-500 p-1.5 rounded-md hover:bg-greentea-50" id="mobile-user-menu-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700" id="mobile-user-menu">
                            <a href="{{ route('login') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                </svg>
                                Register
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="relative">
                    <button class="flex items-center space-x-1 border border-greentea-500 text-greentea-500 px-3 py-1.5 rounded-md hover:bg-greentea-50 transition-colors" id="user-menu-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700" id="user-menu">
                        <a href="{{ route('user.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            My Profile
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                        <form method="POST" action="{{ route('do_logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-greentea-50 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mainNavigation = document.getElementById('main-navigation');
        
        if (mobileMenuButton && mainNavigation) {
            mobileMenuButton.addEventListener('click', function() {
                mainNavigation.classList.toggle('hidden');
            });
        }
        
        // Management dropdown toggle
        const managementButton = document.getElementById('management-dropdown-button');
        const managementMenu = document.getElementById('management-dropdown-menu');
        
        if (managementButton && managementMenu) {
            managementButton.addEventListener('click', function() {
                managementMenu.classList.toggle('hidden');
            });
            
            // Close management dropdown when clicking elsewhere
            document.addEventListener('click', function(event) {
                if (!managementButton.contains(event.target) && !managementMenu.contains(event.target)) {
                    managementMenu.classList.add('hidden');
                }
            });
        }
        
        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });
            
            // Close user menu when clicking elsewhere
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
        
        // Mobile user menu toggle
        const mobileUserMenuButton = document.getElementById('mobile-user-menu-button');
        const mobileUserMenu = document.getElementById('mobile-user-menu');
        
        if (mobileUserMenuButton && mobileUserMenu) {
            mobileUserMenuButton.addEventListener('click', function() {
                mobileUserMenu.classList.toggle('hidden');
            });
            
            // Close mobile user menu when clicking elsewhere
            document.addEventListener('click', function(event) {
                if (!mobileUserMenuButton.contains(event.target) && !mobileUserMenu.contains(event.target)) {
                    mobileUserMenu.classList.add('hidden');
                }
            });
        }
    });
</script>
