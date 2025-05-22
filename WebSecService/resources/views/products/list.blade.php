@extends('layouts.master')
@section('title', 'Premium Teas')
@section('content')

<div class="min-h-screen bg-gradient-to-b from-white to-greentea-50">
    <!-- Hero Header -->
    <div class="relative bg-greentea-800 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-matcha-800 to-bamboo-800 mix-blend-multiply"></div>
            <div class="leaf-pattern-bg absolute inset-0"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">
                    Artisanal Tea Collection
                </h1>
                <p class="text-greentea-100 text-lg md:text-xl max-w-3xl mx-auto">
                    Discover exquisite, handcrafted teas sourced from the world's finest sustainable gardens.
                </p>
                
                @auth
                    @can('add_products')
                        <div class="mt-8">
                            <a href="{{ route('products_edit') }}" class="nature-btn-outline bg-transparent text-white border-white hover:bg-white/10 inline-flex items-center">
                                <i class="bi bi-plus-circle me-2"></i> Add New Product
                            </a>
                        </div>
                    @endcan
                @endauth
            </div>
        </div>
        
        <!-- Decorative Wave Shape -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="w-full h-auto fill-white">
                <path d="M0,96L80,85.3C160,75,320,53,480,58.7C640,64,800,96,960,101.3C1120,107,1280,85,1360,74.7L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Filter Control Panel -->
    <div class="bg-white shadow-lg rounded-xl max-w-7xl mx-auto -mt-16 relative z-10 mb-16 mx-4 sm:mx-8 lg:mx-auto overflow-hidden">
        <div class="p-6">
            <form method="get" action="{{ route('products_list') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="bi bi-search text-greentea-500"></i>
                        </div>
                        <input class="nature-input pl-10 w-full bg-greentea-50/50 border-greentea-200 focus:border-greentea-500 focus:ring-greentea-500 rounded-lg" 
                               placeholder="Search by name, type or origin..." 
                               name="keywords" 
                               value="{{ request()->keywords }}"/>
                    </div>
                    
                    <div class="md:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="bi bi-currency-dollar text-greentea-500"></i>
                        </div>
                        <input class="nature-input pl-10 w-full bg-greentea-50/50 border-greentea-200 rounded-lg" 
                               type="number" 
                               step="0.01" 
                               placeholder="Min Price" 
                               name="min_price" 
                               value="{{ request()->min_price }}"/>
                    </div>
                    
                    <div class="md:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="bi bi-currency-dollar text-greentea-500"></i>
                        </div>
                        <input class="nature-input pl-10 w-full bg-greentea-50/50 border-greentea-200 rounded-lg" 
                               type="number" 
                               step="0.01" 
                               placeholder="Max Price" 
                               name="max_price" 
                               value="{{ request()->max_price }}"/>
                    </div>
                    
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-full bg-greentea-600 hover:bg-greentea-700 text-white rounded-lg transition-colors flex items-center justify-center">
                            <i class="bi bi-funnel text-xl"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Category Navigation -->
        <div class="bg-greentea-50 border-t border-greentea-100 p-4 flex overflow-x-auto scrollbar-hide">
            <div class="flex space-x-2 mx-auto">
                <a href="#" class="px-4 py-2 rounded-full bg-greentea-600 text-white font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-grid me-2"></i> All Teas
                </a>
                <a href="#" class="px-4 py-2 rounded-full hover:bg-greentea-100 text-greentea-700 font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-cup-hot me-2"></i> Green Tea
                </a>
                <a href="#" class="px-4 py-2 rounded-full hover:bg-greentea-100 text-greentea-700 font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-cup me-2"></i> Black Tea
                </a>
                <a href="#" class="px-4 py-2 rounded-full hover:bg-greentea-100 text-greentea-700 font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-flower1 me-2"></i> Herbal
                </a>
                <a href="#" class="px-4 py-2 rounded-full hover:bg-greentea-100 text-greentea-700 font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-stars me-2"></i> Specialty
                </a>
                <a href="#" class="px-4 py-2 rounded-full hover:bg-greentea-100 text-greentea-700 font-medium flex items-center whitespace-nowrap">
                    <i class="bi bi-gift me-2"></i> Gift Sets
                </a>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-green-50 border-l-4 border-green-500 rounded-md p-4 flex items-center">
                <i class="bi bi-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <!-- Collection Title -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-greentea-800 mb-1">Our Collection</h2>
                <p class="text-greentea-600">{{ count($products) }} products</p>
            </div>
            <div class="hidden md:block">
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium bg-greentea-100 text-greentea-800">
                    <i class="bi bi-leaf-fill mr-1"></i> Fresh Arrivals
                </span>
            </div>
        </div>

        @if(count($products) > 0)
            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-12">
                @foreach($products as $product)
                <div class="group relative tea-collection-card">
                    <!-- Price Badge -->
                    <div class="price-badge z-20">
                        ${{ number_format($product->price, 2) }}
                    </div>
                    
                    <!-- Stock Badge -->
                    <div class="absolute top-3 left-3 z-10">
                        @if($product->stock_quantity > 0)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-greentea-100 text-greentea-800">
                                <i class="bi bi-check-circle-fill me-1 text-greentea-500"></i> In Stock
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="bi bi-x-circle-fill me-1 text-red-500"></i> Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Product Image -->
                    <div class="aspect-square rounded-xl bg-greentea-50 overflow-hidden mb-5 shadow-md group-hover:shadow-xl transition-shadow duration-300 product-shimmer-effect">
                        <img src="{{ $product->getMainPhotoUrl() }}" 
                             class="h-full w-full object-cover object-center tea-image-hover" 
                             alt="{{ $product->name }}">
                        
                        <!-- Hover Overlay with Quick Actions -->
                        <div class="absolute inset-0 bg-gradient-to-t from-greentea-900/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                            @auth
                                @can('edit_products')
                                    <div class="flex space-x-2 mb-3 animate-fadeIn">
                                        <a href="{{ route('products_edit', $product->id) }}" 
                                           class="bg-white/90 hover:bg-white text-greentea-800 p-2 rounded-full transition-colors">
                                            <i class="bi bi-pencil text-lg"></i>
                                        </a>
                                        @can('delete_products')
                                            <a href="{{ route('products_delete', $product->id) }}" 
                                               class="bg-white/90 hover:bg-white text-red-600 p-2 rounded-full transition-colors"
                                               onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash text-lg"></i>
                                            </a>
                                        @endcan
                                    </div>
                                @endcan
                            @endauth
                            
                            <!-- Quick View Button -->
                            <a href="#" class="w-full py-2 px-4 bg-white/90 hover:bg-white text-greentea-800 rounded-lg transition-colors flex items-center justify-center font-medium mt-2">
                                <i class="bi bi-eye me-2"></i> Quick View
                            </a>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-greentea-800 group-hover:text-greentea-600 transition-colors duration-300 mb-1 line-clamp-1">
                            {{ $product->name }}
                        </h3>
                        <p class="text-sm text-greentea-600 mb-2 flex items-center">
                            <span class="font-mono text-greentea-500 mr-1">{{ $product->code }}</span>
                            <span class="inline-block w-1 h-1 rounded-full bg-greentea-300 mx-2"></span>
                            <span>{{ $product->model }}</span>
                        </p>
                        <p class="text-sm text-greentea-700 mb-4 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <!-- Product Rating -->
                            <div class="flex items-center">
                                <div class="flex text-amber-400">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <span class="text-xs text-greentea-600 ml-1">(4.5)</span>
                            </div>
                            
                            @auth
                                @can('edit_products')
                                    <span class="text-sm text-greentea-600">
                                        <i class="bi bi-box me-1"></i> Stock: {{ $product->stock_quantity }}
                                    </span>
                                @endcan
                            @endauth
                        </div>

                        <!-- Action Buttons -->
                        @auth
                            @role('Customer')
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 bg-white border border-greentea-200 rounded-lg flex items-center">
                                            <button type="button" class="px-2 py-1 text-greentea-600 hover:bg-greentea-50 rounded-l-lg quantity-btn" data-action="decrease">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                                class="w-10 text-center border-0 focus:ring-0 text-greentea-800 font-medium" style="appearance: textfield;">
                                            <button type="button" class="px-2 py-1 text-greentea-600 hover:bg-greentea-50 rounded-r-lg quantity-btn" data-action="increase" data-max="{{ $product->stock_quantity }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="flex-grow py-2 px-4 bg-greentea-600 hover:bg-greentea-700 text-white rounded-lg transition-colors flex items-center justify-center" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus me-2"></i>
                                            {{ $product->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                        </button>
                                    </div>
                                </form>
                            @endrole
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="w-full py-2 px-4 mt-3 border border-greentea-600 text-greentea-600 hover:bg-greentea-50 rounded-lg transition-colors flex items-center justify-center">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login to Purchase
                            </a>
                        @endguest
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <nav class="inline-flex rounded-md shadow-sm -space-x-px bg-white" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-greentea-200 bg-white text-sm font-medium text-greentea-500 hover:bg-greentea-50">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-greentea-200 bg-greentea-600 text-sm font-medium text-white">
                        1
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-greentea-200 bg-white text-sm font-medium text-greentea-500 hover:bg-greentea-50">
                        2
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-greentea-200 bg-white text-sm font-medium text-greentea-500 hover:bg-greentea-50">
                        3
                    </a>
                    <span class="relative inline-flex items-center px-4 py-2 border border-greentea-200 bg-white text-sm font-medium text-greentea-300">
                        ...
                    </span>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-greentea-200 bg-white text-sm font-medium text-greentea-500 hover:bg-greentea-50">
                        8
                    </a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-greentea-200 bg-white text-sm font-medium text-greentea-500 hover:bg-greentea-50">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-greentea-100 rounded-full flex items-center justify-center mb-6">
                    <i class="bi bi-search text-4xl text-greentea-500"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-greentea-800 mb-3">No products found</h3>
                <p class="text-greentea-600 max-w-md mb-8">We couldn't find any products matching your criteria. Try adjusting your search or explore our other collections.</p>
                
                <a href="{{ route('products_list') }}" class="inline-flex items-center px-6 py-3 border border-greentea-600 text-greentea-600 bg-white hover:bg-greentea-50 rounded-lg transition-colors">
                    <i class="bi bi-arrow-repeat me-2"></i> Reset Filters
                </a>
            </div>
        @endif
        
        <!-- Featured Collection Banner -->
        <div class="mt-20 relative overflow-hidden rounded-2xl bg-gradient-to-r from-matcha-700 to-bamboo-700">
            <div class="absolute inset-0 opacity-10">
                <div class="leaf-pattern-bg absolute inset-0"></div>
            </div>
            
            <div class="relative flex flex-col md:flex-row items-center justify-between px-8 py-10">
                <div class="text-white md:max-w-xl mb-8 md:mb-0">
                    <h3 class="text-xl md:text-3xl font-bold mb-4">Discover Our Seasonal Collection</h3>
                    <p class="text-greentea-100 mb-6">Limited edition teas crafted to celebrate the unique flavors of the season. Available for a limited time only.</p>
                    <a href="#" class="inline-flex items-center bg-white text-greentea-700 px-6 py-3 rounded-lg font-medium hover:bg-greentea-50 transition-colors">
                        <i class="bi bi-star me-2"></i> Shop Collection
                    </a>
                </div>
                
                <div class="w-32 h-32 md:w-48 md:h-48 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <div class="animate-pulse w-24 h-24 md:w-36 md:h-36 bg-white/30 rounded-full flex items-center justify-center">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full flex items-center justify-center">
                            <i class="bi bi-cup-hot text-white text-2xl md:text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity buttons
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('div').querySelector('input');
                const currentValue = parseInt(input.value);
                const action = this.dataset.action;

                if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                } else if (action === 'increase') {
                    const max = parseInt(this.dataset.max);
                    if (currentValue < max) {
                        input.value = currentValue + 1;
                    }
                }
            });
        });

        // Fade in animation for product cards
        const productItems = document.querySelectorAll('.grid > div');
        productItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });
    });
</script>
@endpush

@endsection
