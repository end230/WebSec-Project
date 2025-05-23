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
                        <p class="card-text">{{ str($product->description)->limit(100) }}</p>
                        
                        <!-- Rating and Reviews Summary -->
                        @if($product->getReviewCount() > 0)
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->getAverageRating()))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 small">
                                        {{ number_format($product->getAverageRating(), 1) }} ({{ $product->getReviewCount() }} {{ $product->getReviewCount() === 1 ? 'review' : 'reviews' }})
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Recent Comments -->
                        @if($product->comments->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Recent Reviews:</h6>
                                @foreach($product->comments->take(2) as $comment)
                                    <div class="mb-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="d-flex align-items-center mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $comment->rating)
                                                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                                        @else
                                                            <i class="bi bi-star text-muted" style="font-size: 0.8rem;"></i>
                                                        @endif
                                                    @endfor
                                                    <small class="ms-2 fw-bold">{{ $comment->user->name }}</small>
                                                    @if($comment->is_verified_purchase)
                                                        <span class="badge bg-success ms-1" style="font-size: 0.6rem;">Verified</span>
                                                    @endif
                                                </div>
                                                <p class="mb-0 small">{{ str($comment->comment)->limit(60) }}</p>
                                            </div>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                                @if($product->comments->count() > 2)
                                    <small class="text-muted">
                                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                            View all {{ $product->comments->count() }} reviews
                                        </a>
                                    </small>
                                @endif
                            </div>
                        @endif

                        <!-- Quick Review Section -->
                        @auth
                            @if(isset($userPurchases[$product->id]) && !isset($userComments[$product->id]))
                                <!-- User can leave a quick review -->
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-success mb-2">
                                        <i class="bi bi-check-circle me-1"></i>You can review this product!
                                    </h6>
                                    <form action="{{ route('products.comments.store', $product) }}" method="POST" class="quick-review-form">
                                        @csrf
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <small class="me-2">Rating:</small>
                                                <div class="rating-quick">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" name="rating" value="{{ $i }}" id="quick-star{{ $i }}-{{ $product->id }}" required>
                                                        <label for="quick-star{{ $i }}-{{ $product->id }}" class="star-quick">
                                                            <i class="bi bi-star-fill"></i>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <textarea name="comment" class="form-control form-control-sm" rows="2" 
                                                placeholder="Write your review..." maxlength="500" required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-send me-1"></i>Submit Review
                                            </button>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            @elseif(isset($userComments[$product->id]))
                                <!-- User has already reviewed -->
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-success py-2 mb-2">
                                        <small><i class="bi bi-check-circle me-1"></i>You've reviewed this product!</small>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i>View Your Review
                                    </a>
                                </div>
                            @elseif(!isset($userPurchases[$product->id]))
                                <!-- User hasn't purchased -->
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-warning py-2 mb-2">
                                        <small><i class="bi bi-info-circle me-1"></i>Purchase to review this product</small>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Guest user -->
                            @if($product->comments->count() === 0)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="alert alert-info py-2 mb-2">
                                        <small><i class="bi bi-info-circle me-1"></i>No reviews yet. Be the first!</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="bi bi-person me-1"></i>Sign In
                                        </a>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="bi bi-eye me-1"></i>Details
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="bi bi-person me-1"></i>Sign In to Review
                                        </a>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endauth
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

        // Quick review form handling
        const quickReviewForms = document.querySelectorAll('.quick-review-form');
        quickReviewForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Submitting...';
            });
        });
    });
</script>

<style>
/* Quick rating styles */
.rating-quick {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-quick input[type="radio"] {
    display: none;
}

.rating-quick label.star-quick {
    font-size: 1rem;
    color: #ddd;
    cursor: pointer;
    margin: 0 1px;
    transition: color 0.3s;
}

.rating-quick label.star-quick:hover,
.rating-quick label.star-quick:hover ~ label.star-quick,
.rating-quick input[type="radio"]:checked ~ label.star-quick {
    color: #ffc107;
}

/* Product card enhancements */
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

/* Comment section styles */
.product-card .bg-light {
    border-left: 3px solid #dee2e6;
}

.product-card .alert {
    border: none;
    border-radius: 6px;
}

.product-card .border-top {
    border-color: #e9ecef !important;
}

/* Button group spacing */
.btn-group .btn {
    border-radius: 4px !important;
}

.btn-group .btn:not(:last-child) {
    margin-right: 4px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-card .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .product-card .d-flex.gap-2 .btn {
        margin-bottom: 4px;
    }
}
</style>
@endpush

@endsection
