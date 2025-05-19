@extends('layouts.master')
@section('title', 'Products')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Products</h2>
        @auth
            @if(auth()->user()->hasPermissionTo('add_products'))
                <div class="mb-4">
                    <a href="{{ route('products_edit') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Add New Product
                    </a>
                </div>
            @endif
        @endauth
    </div>
    <div class="card-body">
        <form method="get" action="{{ route('products_list') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                        <input class="form-control border-start-0" placeholder="Search by name" name="keywords" value="{{ request()->keywords }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-currency-dollar"></i></span>
                        <input class="form-control border-start-0" type="number" step="0.01" placeholder="Min Price" name="min_price" value="{{ request()->min_price }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-currency-dollar"></i></span>
                        <input class="form-control border-start-0" type="number" step="0.01" placeholder="Max Price" name="max_price" value="{{ request()->max_price }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Product grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100 product-card animate__animated animate__fadeIn" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="position-absolute top-0 end-0 p-3">
                        @if($product->stock_quantity > 0)
                            <span class="badge bg-success">In Stock</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>

                    <img src="{{ $product->getMainPhotoUrl() }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <h5 class="text-primary">${{ number_format($product->price, 2) }}</h5>
                        </div>
                        <p class="card-text text-muted small mb-2">{{ $product->code }} | {{ $product->model }}</p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                    </div>

                    <div class="card-footer bg-transparent border-top-0">
                        @auth
                            @if(auth()->user()->hasPermissionTo('edit_products'))
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Stock: {{ $product->stock_quantity }}</span>
                                    <div>
                                        <a href="{{ route('products_edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        @if(auth()->user()->hasPermissionTo('delete_products'))
                                            <a href="{{ route('products_delete', $product->id) }}" class="btn btn-sm btn-outline-danger ms-1"
                                               onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if(auth()->user()->hasRole('Customer'))
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <div class="d-flex align-items-center">
                                        <div class="input-group me-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="decrease">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                                   class="form-control form-control-sm text-center quantity-input" style="width: 50px;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-action="increase" data-max="{{ $product->stock_quantity }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-primary flex-grow-1 add-to-cart-btn" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus me-1"></i>
                                            {{ $product->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login to Purchase
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty state -->
        @if(count($products) == 0)
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
            <h3 class="mt-3">No products found</h3>
            <p class="text-muted">Try adjusting your search criteria</p>
            <a href="{{ route('products_list') }}" class="btn btn-outline-primary mt-2">Clear Filters</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity buttons
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('.quantity-input');
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

        // Add to cart animation
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.disabled) {
                    const card = this.closest('.product-card');
                    card.classList.add('animate__animated', 'animate__pulse');

                    setTimeout(() => {
                        card.classList.remove('animate__animated', 'animate__pulse');
                    }, 1000);
                }
            });
        });
    });
</script>
@endpush

@endsection
