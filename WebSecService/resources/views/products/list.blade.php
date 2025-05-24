@extends('layouts.master')
@section('title', 'Our Tea Collection')
@section('content')

<!-- Hero Section with Animated Tea Background -->
<div class="tea-hero-section">
    <div class="tea-leaves-container">
        @for ($i = 1; $i <= 20; $i++)
            <div class="floating-tea-leaf" style="--delay: {{ $i * 0.3 }}s; --size: {{ rand(20, 40) }}px;"></div>
        @endfor
    </div>
    <div class="steam-particles">
        @for ($i = 1; $i <= 15; $i++)
            <div class="steam-particle" style="--delay: {{ $i * 0.2 }}s;"></div>
        @endfor
    </div>
    <div class="container position-relative">
        <h1 class="display-4 text-center mb-4">Discover Our Tea Collection</h1>
        
        <!-- Enhanced Search and Filter Section -->
        <div class="search-container" data-aos="fade-up">
            <form method="get" action="{{ route('products_list') }}" class="search-form">
                <div class="search-grid">
                    <div class="search-field">
                        <div class="nature-input-group">
                            <i class="bi bi-search"></i>
                            <input type="text" class="nature-input" placeholder="Search your perfect tea..." name="keywords" value="{{ request()->keywords }}">
                            <div class="nature-input-decoration"></div>
                        </div>
                    </div>
                    <div class="search-field">
                        <div class="nature-input-group">
                            <i class="bi bi-currency-dollar"></i>
                            <input type="number" class="nature-input" step="0.01" placeholder="Minimum Price" name="min_price" value="{{ request()->min_price }}">
                            <div class="nature-input-decoration"></div>
                        </div>
                    </div>
                    <div class="search-field">
                        <div class="nature-input-group">
                            <i class="bi bi-currency-dollar"></i>
                            <input type="number" class="nature-input" step="0.01" placeholder="Maximum Price" name="max_price" value="{{ request()->max_price }}">
                            <div class="nature-input-decoration"></div>
                        </div>
                    </div>
                    <div class="search-field">
                        <button type="submit" class="btn-nature-search">
                            <span>Find Your Tea</span>
                            <i class="bi bi-cup-hot"></i>
                            <div class="btn-nature-decoration"></div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container py-5">
    @auth
        @if(auth()->user()->hasPermissionTo('add_products'))
            <div class="text-end mb-4" data-aos="fade-left">
                <a href="{{ route('products_edit') }}" class="btn-nature-add">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span>Add New Tea</span>
                    <div class="btn-nature-decoration"></div>
                </a>
            </div>
        @endif
    @endauth

    @if(session('success'))
        <div class="nature-alert success animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Product Grid -->
    <div class="tea-products-grid">
        @foreach($products as $product)
        <div class="tea-product-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="tea-product-image">
                <img src="{{ $product->getMainPhotoUrl() }}" alt="{{ $product->name }}">
                <div class="tea-product-overlay">
                    <div class="tea-steam">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="steam-curl" style="--i: {{ $i }}"></div>
                        @endfor
                    </div>
                </div>
                @if($product->stock_quantity > 0)
                    <div class="stock-badge in-stock">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>In Stock</span>
                    </div>
                @else
                    <div class="stock-badge out-of-stock">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Out of Stock</span>
                    </div>
                @endif
            </div>

            @if($product->comments->count() > 0)
                <button type="button" class="comments-toggle" title="Show Reviews">
                    <i class="bi bi-chat-dots"></i>
                    <span class="comments-count">{{ $product->comments->count() }}</span>
                </button>
            @endif

            <div class="tea-product-content">
                <h3 class="tea-product-title">{{ $product->name }}</h3>
                <div class="tea-product-meta">
                    <span class="tea-product-code">{{ $product->code }}</span>
                    <span class="tea-product-model">{{ $product->model }}</span>
                </div>
                <p class="tea-product-description">{{ str($product->description)->limit(100) }}</p>
                
                @if($product->getReviewCount() > 0)
                    <div class="tea-product-rating">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->getAverageRating()))
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-count">
                            {{ number_format($product->getAverageRating(), 1) }}
                            ({{ $product->getReviewCount() }})
                        </span>
                    </div>
                @endif

                @if($product->comments->count() > 0)
                    <div class="tea-comments-preview">
                        <div class="comments-header">
                            <h4>Recent Reviews</h4>
                            <button type="button" class="comments-close" title="Close Reviews">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="comments-scroll">
                            @foreach($product->comments->take(2) as $comment)
                                <div class="comment-card">
                                    <div class="comment-header">
                                        <div class="comment-avatar">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div class="comment-meta">
                                            <span class="comment-author">{{ $comment->user->name }}</span>
                                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        <div class="comment-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $comment->rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p>{{ str($comment->comment)->limit(100) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($product->comments->count() > 2)
                            <a href="{{ route('products.show', $product) }}" class="view-all-reviews">
                                View all {{ $product->comments->count() }} reviews
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Quick Review Section -->
                @auth
                    @if(isset($userPurchases[$product->id]) && !isset($userComments[$product->id]))
                        <div class="quick-review-form">
                            <h4>Share Your Experience</h4>
                            <form action="{{ route('products.comment', $product) }}" method="POST">
                                @csrf
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}_{{ $product->id }}" name="rating" value="{{ $i }}" required>
                                        <label for="star{{ $i }}_{{ $product->id }}">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                                <div class="nature-input-group">
                                    <textarea name="comment" class="nature-input" placeholder="Share your thoughts..." required></textarea>
                                    <div class="nature-input-decoration"></div>
                                </div>
                                <button type="submit" class="btn-nature-submit">
                                    <i class="bi bi-send"></i>
                                    <span>Submit Review</span>
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                <div class="tea-product-footer">
                    <div class="tea-product-price">${{ number_format($product->price, 2) }}</div>
                    
                    @auth
                        @if(auth()->user()->hasPermissionTo('edit_products'))
                            <div class="admin-controls">
                                <a href="{{ route('products_edit', $product->id) }}" class="btn-nature-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->user()->hasPermissionTo('delete_products'))
                                    <a href="{{ route('products_delete', $product->id) }}" 
                                       class="btn-nature-delete"
                                       onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('cart.add', $product) }}" method="POST" class="cart-form">
                            @csrf
                            <div class="quantity-control">
                                <button type="button" class="btn-quantity" data-action="decrease">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="quantity" value="1" min="1" 
                                       max="{{ $product->stock_quantity }}" class="quantity-input">
                                <button type="button" class="btn-quantity" data-action="increase" 
                                        data-max="{{ $product->stock_quantity }}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn-nature-cart" 
                                    {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus"></i>
                                <span>{{ $product->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}</span>
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn-nature-login">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login to Purchase</span>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty state -->
    @if(count($products) == 0)
    <div class="empty-state" data-aos="fade-up">
        <div class="empty-state-icon">
            <i class="bi bi-search"></i>
            <div class="empty-state-decoration"></div>
        </div>
        <h3>No Teas Found</h3>
        <p>Try adjusting your search criteria</p>
        <a href="{{ route('products_list') }}" class="btn-nature-reset">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Clear Filters</span>
        </a>
    </div>
    @endif
</div>

<style>
/* Tea-themed Variables */
:root {
    --tea-green: #2F5233;
    --tea-brown: #8B4513;
    --tea-sage: #9CAF88;
    --tea-cream: #FFFDD0;
    --tea-dark: #3E2723;
    --animation-slow: 3s;
    --animation-medium: 2s;
    --animation-fast: 0.3s;
}

/* Hero Section */
.tea-hero-section {
    position: relative;
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--tea-sage) 0%, var(--tea-green) 100%);
    overflow: hidden;
    color: white;
}

/* Floating Tea Leaves */
.tea-leaves-container {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.floating-tea-leaf {
    position: absolute;
    width: var(--size);
    height: var(--size);
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(255,255,255,0.2)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E") center/contain no-repeat;
    animation: floatLeaf var(--animation-slow) ease-in-out infinite;
    animation-delay: var(--delay);
}

/* Steam Particles */
.steam-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.steam-particle {
    position: absolute;
    width: 8px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    animation: steamRise var(--animation-medium) ease-out infinite;
    animation-delay: var(--delay);
}

/* Search Container */
.search-container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-top: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

/* Nature-inspired Input Groups */
.nature-input-group {
    position: relative;
    overflow: hidden;
}

.nature-input {
    width: 100%;
    padding: 1rem 1rem 1rem 2.5rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transition: all var(--animation-fast) ease;
}

.nature-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.nature-input:focus {
    border-color: var(--tea-cream);
    background: rgba(255, 255, 255, 0.2);
    outline: none;
}

.nature-input-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    opacity: 0.8;
}

.nature-input-decoration {
    position: absolute;
    right: -20px;
    bottom: -20px;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    transition: all var(--animation-fast) ease;
}

.nature-input:focus ~ .nature-input-decoration {
    transform: scale(2.5);
    opacity: 0.2;
}

/* Nature-inspired Buttons */
.btn-nature-search {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 15px;
    background: var(--tea-cream);
    color: var(--tea-green);
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
    transition: all var(--animation-fast) ease;
}

.btn-nature-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-nature-decoration {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    transform: translate(-50%, -50%) scale(0);
    transition: transform var(--animation-fast) ease;
}

.btn-nature-search:hover .btn-nature-decoration {
    transform: translate(-50%, -50%) scale(1);
}

/* Product Grid */
.tea-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

/* Product Card */
.tea-product-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all var(--animation-fast) ease;
    position: relative;
    border: 1px solid rgba(156, 175, 136, 0.1);
}

.tea-product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.tea-product-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
}

.tea-product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--animation-fast) ease;
}

.tea-product-card:hover .tea-product-image img {
    transform: scale(1.1);
}

/* Product Content */
.tea-product-content {
    padding: 1.5rem;
    background: transparent;
}

.tea-product-title {
    font-size: 1.5rem;
    color: var(--tea-dark);
    margin-bottom: 0.5rem;
}

.tea-product-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
    color: var(--tea-sage);
    margin-bottom: 1rem;
}

/* Rating Stars */
.rating-stars {
    color: #FFD700;
    display: flex;
    gap: 0.2rem;
}

/* Comments section */
.tea-comments-preview {
    height: 0;
    overflow: hidden;
    opacity: 0;
    margin-top: 0;
    transition: all 0.3s ease-in-out;
    border-top: 1px solid rgba(156, 175, 136, 0.1);
    padding: 0;
}

.tea-comments-preview.active {
    height: auto;
    opacity: 1;
    margin-top: 1rem;
    padding-top: 1rem;
}

/* Debug styles to ensure visibility */
.tea-comments-preview.active .comments-header,
.tea-comments-preview.active .comments-scroll,
.tea-comments-preview.active .view-all-reviews {
    opacity: 1;
    transform: translateY(0);
}

.comments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.comments-close {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--animation-fast) ease;
}

.comments-close:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(90deg);
}

[data-theme="dark"] .comments-close:hover {
    background: rgba(47, 82, 51, 0.1);
}

.comments-scroll {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 1rem;
}

.comment-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(156, 175, 136, 0.1);
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 15px;
    transition: all var(--animation-fast) ease;
}

[data-theme="dark"] .comment-card {
    background: rgba(47, 82, 51, 0.05);
    border-color: rgba(156, 175, 136, 0.05);
}

.comment-card:hover {
    transform: translateX(5px);
    border-color: var(--tea-sage);
    background: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .comment-card:hover {
    background: rgba(47, 82, 51, 0.1);
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.comment-avatar {
    width: 40px;
    height: 40px;
    background: var(--tea-green);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* Cart Controls */
.cart-form {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.quantity-control {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(156, 175, 136, 0.2);
}

.btn-quantity {
    background: none;
    border: none;
    padding: 0.5rem 1rem;
    color: var(--tea-green);
    cursor: pointer;
    transition: all var(--animation-fast) ease;
}

.btn-quantity:hover {
    background: var(--tea-sage);
    color: white;
}

.quantity-input {
    background: transparent;
    border-left: 1px solid rgba(156, 175, 136, 0.2);
    border-right: 1px solid rgba(156, 175, 136, 0.2);
}

/* Animations */
@keyframes floatLeaf {
    0%, 100% {
        transform: translateY(0) rotate(0);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

@keyframes steamRise {
    0% {
        transform: translateY(100%) translateX(0) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100%) translateX(50px) scale(2);
        opacity: 0;
    }
}

/* Dark Mode Support */
[data-theme="dark"] {
    .tea-product-card {
        background: rgba(47, 82, 51, 0.05);
        border-color: rgba(156, 175, 136, 0.05);
    }
    
    .tea-product-title {
        color: var(--text-color);
    }
    
    .comment-card {
        background: rgba(47, 82, 51, 0.1);
        border-color: rgba(156, 175, 136, 0.05);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-grid {
        grid-template-columns: 1fr;
    }
    
    .tea-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

/* Enhanced Add to Cart Button */
.btn-nature-cart {
    flex: 1;
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 15px;
    background: linear-gradient(135deg, var(--tea-green), var(--tea-sage));
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
    transition: all var(--animation-fast) cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.btn-nature-cart:not(:disabled):hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 4px 15px rgba(47, 82, 51, 0.2);
}

.btn-nature-cart:disabled {
    background: linear-gradient(135deg, #ccc, #999);
    cursor: not-allowed;
}

.btn-nature-cart::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--tea-sage), var(--tea-green));
    opacity: 0;
    transition: opacity var(--animation-fast) ease;
}

.btn-nature-cart:not(:disabled):hover::before {
    opacity: 1;
}

.btn-nature-cart span,
.btn-nature-cart i {
    position: relative;
    z-index: 1;
}

/* Comments toggle button */
.comments-toggle {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--animation-fast) ease;
    z-index: 2;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

[data-theme="dark"] .comments-toggle {
    background: rgba(47, 82, 51, 0.15);
    border-color: rgba(156, 175, 136, 0.2);
    color: var(--text-color);
}

.comments-toggle:hover {
    transform: scale(1.1);
    background: rgba(255, 255, 255, 0.25);
}

[data-theme="dark"] .comments-toggle:hover {
    background: rgba(47, 82, 51, 0.25);
}

/* Comments animation */
@keyframes commentSlideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes commentSlideUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity Control
    document.querySelectorAll('.btn-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('max'));
            
            if (this.dataset.action === 'increase' && currentValue < maxValue) {
                input.value = currentValue + 1;
            } else if (this.dataset.action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    // Parallax Effect for Floating Leaves
    document.addEventListener('mousemove', function(e) {
        const leaves = document.querySelectorAll('.floating-tea-leaf');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        leaves.forEach(leaf => {
            const speed = 30;
            const x = (mouseX * speed);
            const y = (mouseY * speed);
            leaf.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Smooth Scroll for Comments
    document.querySelectorAll('.comments-scroll').forEach(scroll => {
        new SimpleBar(scroll, {
            autoHide: false
        });
    });

    // Add ripple effect to cart buttons
    document.querySelectorAll('.btn-nature-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size/2;
            const y = e.clientY - rect.top - size/2;
            
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    });

    // Debug log to check if the script is running
    console.log('Comments toggle script initialized');

    // Updated comments toggle functionality with debug logs
    document.querySelectorAll('.comments-toggle, .comments-close').forEach(button => {
        console.log('Found toggle button:', button);
        
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            console.log('Toggle button clicked');
            
            const card = this.closest('.tea-product-card');
            const commentsSection = card.querySelector('.tea-comments-preview');
            
            console.log('Comments section found:', commentsSection);
            console.log('Current active state:', commentsSection.classList.contains('active'));
            
            const isClosing = this.classList.contains('comments-close');
            
            if (commentsSection.classList.contains('active') || isClosing) {
                console.log('Closing comments section');
                commentsSection.classList.remove('active');
            } else {
                console.log('Opening comments section');
                commentsSection.classList.add('active');
                
                // Get the height of the comments section
                const height = commentsSection.scrollHeight;
                console.log('Comments section height:', height);
                
                // Ensure the section is visible
                commentsSection.style.display = 'block';
                
                // Force a reflow to ensure the transition works
                commentsSection.offsetHeight;
            }
        });
    });
});
</script>
@endsection
