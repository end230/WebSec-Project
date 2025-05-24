@extends('layouts.master')

@section('title', $product->name)

@section('content')
<div class="tea-garden-details">
    <!-- Enhanced Falling Leaves Animation -->
    <div class="leaves">
        @for($i = 1; $i <= 40; $i++)
            <div class="leaf leaf-{{ rand(1, 4) }}" style="--i: {{ $i }}">
                @switch(rand(1, 4))
                    @case(1) 🍃 @break
                    @case(2) 🌿 @break
                    @case(3) ☘️ @break
                    @case(4) 🍂 @break
                @endswitch
            </div>
        @endfor
    </div>

    <div class="container py-5">
        <div class="product-details-card">
            <!-- Product Header -->
            <div class="product-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products_list') }}">Products</a></li>
                        <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
            </div>

            <!-- Product Content -->
    <div class="row">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="product-gallery">
                        <div class="main-image">
                            <img src="{{ $product->getMainPhotoUrl() }}" alt="{{ $product->name }}" class="img-fluid">
                            <div class="tea-steam-wrapper">
                                @for($i = 1; $i <= 7; $i++)
                                    <div class="steam" style="--i: {{ $i }}"></div>
                                @endfor
                            </div>
                        </div>
                        @if($product->additional_photos)
                            <div class="thumbnail-gallery mt-3">
                                @foreach($product->getAllPhotoUrls() as $photo)
                                    <div class="thumbnail">
                                        <img src="{{ $photo }}" alt="{{ $product->name }}" class="img-fluid">
                                    </div>
                                @endforeach
                        </div>
                    @endif
                </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-meta">
                            <span class="product-code">Code: {{ $product->code }}</span>
                            <span class="product-model">Model: {{ $product->model }}</span>
                        </div>

                        <div class="product-rating mt-3">
                            @php
                                $avgRating = $product->getAverageRating();
                                $reviewCount = $product->getReviewCount();
                            @endphp
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <i class="bi bi-star-fill text-success"></i>
                                    @else
                                        <i class="bi bi-star text-success"></i>
                                    @endif
                                @endfor
                                <span class="rating-count">({{ $reviewCount }} reviews)</span>
                            </div>
                        </div>

                        <div class="product-price mt-4">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                            @if($product->isInStock())
                                <span class="stock-badge in-stock">
                                    <i class="bi bi-check-circle-fill"></i> In Stock
                                </span>
                        @else
                                <span class="stock-badge out-of-stock">
                                    <i class="bi bi-x-circle-fill"></i> Out of Stock
                                </span>
                        @endif
                    </div>
                    
                        <div class="product-description mt-4">
                            <h5>Description</h5>
                            <p>{{ $product->description }}</p>
                        </div>

                        @auth
                            <div class="product-actions mt-4">
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-3">
                            @csrf
                                    <div class="quantity-control">
                                        <button type="button" class="btn-quantity" data-action="decrease">-</button>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="quantity-input">
                                        <button type="button" class="btn-quantity" data-action="increase">+</button>
                                </div>
                                    <button type="submit" class="btn btn-success flex-grow-1" {{ !$product->isInStock() ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        @else
                        <div class="mt-4">
                                <a href="{{ route('login') }}" class="btn btn-outline-success">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Purchase
                                </a>
                            </div>
                        @endauth
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
            <div class="reviews-section mt-5">
                <h3 class="section-title">Customer Reviews</h3>
                
                    @auth
                        @if($hasPurchased && !$existingComment)
                        <div class="review-form-card">
                            <h4>Write a Review</h4>
                            <form action="{{ route('products.comments.store', $product) }}" method="POST">
                                @csrf
                                <div class="rating-input mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="stars-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                            <label for="star{{ $i }}">
                                                        <i class="bi bi-star-fill"></i>
                                                    </label>
                                                @endfor
                                    </div>
                                </div>
                                    <div class="mb-3">
                                    <label for="comment" class="form-label">Your Review</label>
                                    <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-send"></i> Submit Review
                                    </button>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Reviews List -->
                <div class="reviews-list mt-4">
                    @forelse($comments as $comment)
                        <div class="review-card" data-aos="fade-up">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="avatar">{{ substr($comment->user->name, 0, 1) }}</div>
                                    <div class="reviewer-meta">
                                        <h5>{{ $comment->user->name }}</h5>
                                        <span class="review-date">{{ $comment->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $comment->rating)
                                            <i class="bi bi-star-fill text-success"></i>
                                                    @else
                                            <i class="bi bi-star text-success"></i>
                                                    @endif
                                                @endfor
                                </div>
                            </div>
                            <div class="review-content">
                                <p>{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="no-reviews text-center py-5">
                            <i class="bi bi-chat-square-text display-4 text-muted"></i>
                            <h4 class="mt-3">No Reviews Yet</h4>
                            <p class="text-muted">Be the first to review this product!</p>
                        </div>
                    @endforelse

                    @if($comments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Green Tea Theme for Details Page */
.tea-garden-details {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #f1f8e9 0%, #dcedc8 50%, #c8e6c9 100%);
    overflow: hidden;
}

/* Enhanced Falling Leaves Animation */
.leaves {
    position: fixed;
    top: -10%;
    left: 0;
    width: 100%;
    height: 120%;
    pointer-events: none;
    z-index: 1;
}

.leaf {
    position: absolute;
    animation: falling 15s linear infinite;
    opacity: 0.7;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.leaf-1 { font-size: 20px; }
.leaf-2 { font-size: 24px; }
.leaf-3 { font-size: 28px; }
.leaf-4 { font-size: 32px; }

@keyframes falling {
    0% {
        transform: translateX(var(--x-start)) translateY(-10%) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 0.7;
    }
    90% {
        opacity: 0.7;
    }
    100% {
        transform: translateX(var(--x-end)) translateY(110vh) rotate(360deg);
        opacity: 0;
    }
}

/* Product Details Card */
.product-details-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    position: relative;
    z-index: 2;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Product Gallery */
.product-gallery {
    position: relative;
}

.main-image {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.main-image img {
    width: 100%;
    height: auto;
    transition: transform 0.5s ease;
}

.main-image:hover img {
    transform: scale(1.05);
}

.thumbnail-gallery {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 1rem 0;
}

.thumbnail {
    flex: 0 0 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.thumbnail:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Enhanced Steam Animation */
.tea-steam-wrapper {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 80px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.main-image:hover .tea-steam-wrapper {
    opacity: 1;
}

.steam {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 8px;
    height: 50px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 10px;
    animation: steaming 3s infinite;
    opacity: 0;
    filter: blur(5px);
}

@keyframes steaming {
    0% {
        transform: translateY(0) translateX(-50%) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 0.8;
    }
    100% {
        transform: translateY(-40px) translateX(-50%) scale(2);
        opacity: 0;
    }
}

.steam:nth-child(1) { animation-delay: 0.2s; }
.steam:nth-child(2) { animation-delay: 0.4s; }
.steam:nth-child(3) { animation-delay: 0.6s; }
.steam:nth-child(4) { animation-delay: 0.8s; }
.steam:nth-child(5) { animation-delay: 1.0s; }
.steam:nth-child(6) { animation-delay: 1.2s; }
.steam:nth-child(7) { animation-delay: 1.4s; }

/* Product Info Styling */
.product-title {
    font-size: 2rem;
    color: #2e7d32;
    margin-bottom: 1rem;
}

.product-meta {
    display: flex;
    gap: 1.5rem;
    color: #666;
    font-size: 0.9rem;
}

.product-price {
    font-size: 2rem;
    color: #1b5e20;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stock-badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.stock-badge.in-stock {
    background: #c8e6c9;
    color: #2e7d32;
}

.stock-badge.out-of-stock {
    background: #ffcdd2;
    color: #c62828;
}

/* Quantity Control */
.quantity-control {
    display: flex;
    align-items: center;
    background: #f5f5f5;
    border-radius: 25px;
    padding: 0.5rem;
}

.btn-quantity {
    width: 40px;
    height: 40px;
    border: none;
    background: white;
    border-radius: 20px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-quantity:hover {
    background: #4caf50;
    color: white;
}

.quantity-input {
    width: 60px;
    text-align: center;
    border: none;
    background: none;
    font-size: 1.1rem;
    font-weight: 500;
}

/* Reviews Section */
.reviews-section {
    margin-top: 4rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.section-title {
    color: #2e7d32;
    margin-bottom: 2rem;
}

.review-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.review-card:hover {
    transform: translateY(-5px);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.avatar {
    width: 40px;
    height: 40px;
    background: #4caf50;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.reviewer-meta h5 {
    margin: 0;
    color: #2e7d32;
}

.review-date {
    font-size: 0.9rem;
    color: #666;
}

.review-rating {
    color: #4caf50;
}

.review-content {
    color: #333;
    line-height: 1.6;
}

/* Review Form */
.review-form-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.stars-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.5rem;
}

.stars-input input {
    display: none;
}

.stars-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    transition: all 0.2s ease;
}

.stars-input label:hover,
.stars-input label:hover ~ label,
.stars-input input:checked ~ label {
    color: #4caf50;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-details-card {
        padding: 1rem;
    }

    .product-title {
    font-size: 1.5rem;
    }

    .product-price {
        font-size: 1.5rem;
    }

    .product-actions {
        flex-direction: column;
    }

    .quantity-control {
        margin-bottom: 1rem;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-out',
        once: true
    });

    // Enhanced leaf animation
    const leaves = document.querySelectorAll('.leaf');
    leaves.forEach(leaf => {
        const randomStart = Math.random() * 100;
        const randomEnd = Math.random() * 100;
        const randomRotation = Math.random() * 360;
        const randomDelay = Math.random() * 15;
        const randomDuration = 15 + Math.random() * 5;

        leaf.style.setProperty('--x-start', `${randomStart}%`);
        leaf.style.setProperty('--x-end', `${randomEnd}%`);
        leaf.style.setProperty('--rotation', `${randomRotation}deg`);
        leaf.style.animationDelay = `${randomDelay}s`;
        leaf.style.animationDuration = `${randomDuration}s`;
    });

    // Quantity control
    const quantityBtns = document.querySelectorAll('.btn-quantity');
    quantityBtns.forEach(btn => {
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

    // Thumbnail gallery
    const thumbnails = document.querySelectorAll('.thumbnail img');
    const mainImage = document.querySelector('.main-image img');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            mainImage.src = this.src;
            thumbnails.forEach(t => t.parentElement.classList.remove('active'));
            this.parentElement.classList.add('active');
            });
        });
    });
</script>
@endsection 