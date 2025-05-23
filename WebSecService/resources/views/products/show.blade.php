@extends('layouts.master')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products_list') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-body text-center p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    @else
                        <div class="bg-light rounded p-5 d-flex align-items-center justify-content-center" style="height: 300px;">
                            <span class="text-muted fs-3">No image available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="mb-0">{{ $product->name }}</h2>
                </div>
                <div class="card-body">
                    <h3 class="text-primary mb-4">${{ number_format($product->price, 2) }}</h3>
                    
                    <!-- Rating Summary -->
                    @if($product->getReviewCount() > 0)
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->getAverageRating()))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">
                                    {{ number_format($product->getAverageRating(), 1) }} out of 5 
                                    ({{ $product->getReviewCount() }} {{ $product->getReviewCount() === 1 ? 'review' : 'reviews' }})
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $product->description ?: 'No description available.' }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Stock Status</h5>
                        @if($product->in_stock)
                            <span class="badge bg-success">In Stock</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>
                    
                    @if($product->in_stock)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-auto">
                                    <label for="quantity" class="col-form-label">Quantity:</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="100">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary add-to-cart-btn">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->hasPermissionTo('edit_products'))
                        <div class="mt-4">
                            <a href="{{ route('products_edit', $product) }}" class="btn btn-secondary me-2">
                                <i class="bi bi-pencil me-1"></i> Edit Product
                            </a>
                            <form action="{{ route('products_delete', $product) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash me-1"></i> Delete Product
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>Customer Reviews
                        @if($product->getReviewCount() > 0)
                            <span class="badge bg-primary ms-2">{{ $product->getReviewCount() }}</span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Review Form -->
                    @auth
                        @if($hasPurchased && !$existingComment)
                            <!-- User has purchased and can review -->
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                You've purchased this product! Share your experience to help other customers.
                            </div>
                            
                            <form action="{{ route('products.comments.store', $product) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Write a Review</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Rating -->
                                        <div class="mb-3">
                                            <label class="form-label">Rating *</label>
                                            <div class="rating-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                                    <label for="star{{ $i }}" class="star">
                                                        <i class="bi bi-star-fill"></i>
                                                    </label>
                                                @endfor
                                            </div>
                                            @error('rating')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <!-- Comment -->
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Your Review *</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="4" 
                                                placeholder="Share your experience with this product..." 
                                                maxlength="1000" required>{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i>Submit Review
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif($existingComment)
                            <!-- User has already reviewed -->
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                Thank you! You have already reviewed this product.
                            </div>
                            
                            <!-- Show disabled form with their existing review -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Your Review</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Your Rating</label>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $existingComment->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $existingComment->rating }}/5</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Your Review</label>
                                        <div class="p-3 bg-light rounded">
                                            {{ $existingComment->comment }}
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Reviewed on {{ $existingComment->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        @elseif(!$hasPurchased)
                            <!-- User hasn't purchased - show disabled form with message -->
                            <div class="alert alert-warning mb-4">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                You can only review products you have purchased and received.
                            </div>
                            
                            <div class="card mb-4 opacity-75">
                                <div class="card-header">
                                    <h5 class="mb-0">Write a Review</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Disabled Rating -->
                                    <div class="mb-3">
                                        <label class="form-label">Rating *</label>
                                        <div class="rating-input-disabled">
                                            @for($i = 5; $i >= 1; $i--)
                                                <span class="star-disabled">
                                                    <i class="bi bi-star text-muted"></i>
                                                </span>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <!-- Disabled Comment -->
                                    <div class="mb-3">
                                        <label for="comment-disabled" class="form-label">Your Review *</label>
                                        <textarea id="comment-disabled" class="form-control" rows="4" 
                                            placeholder="Purchase this product to leave a review..." 
                                            disabled readonly></textarea>
                                    </div>
                                    
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="bi bi-lock me-1"></i>Purchase Required to Review
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Guest user - show disabled form with login prompt -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <a href="{{ route('login') }}">Sign in</a> to write a review for this product.
                        </div>
                        
                        <div class="card mb-4 opacity-75">
                            <div class="card-header">
                                <h5 class="mb-0">Write a Review</h5>
                            </div>
                            <div class="card-body">
                                <!-- Disabled Rating -->
                                <div class="mb-3">
                                    <label class="form-label">Rating *</label>
                                    <div class="rating-input-disabled">
                                        @for($i = 5; $i >= 1; $i--)
                                            <span class="star-disabled">
                                                <i class="bi bi-star text-muted"></i>
                                            </span>
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Disabled Comment -->
                                <div class="mb-3">
                                    <label for="comment-guest" class="form-label">Your Review *</label>
                                    <textarea id="comment-guest" class="form-control" rows="4" 
                                        placeholder="Sign in to leave a review..." 
                                        disabled readonly></textarea>
                                </div>
                                
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Sign In to Review
                                </a>
                            </div>
                        </div>
                    @endauth
                    
                    <!-- Existing Reviews -->
                    @if($comments->count() > 0)
                        <h5 class="mb-3">Customer Reviews</h5>
                        @foreach($comments as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $comment->rating)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 fw-bold">{{ $comment->rating }}/5</span>
                                                @if($comment->is_verified_purchase)
                                                    <span class="badge bg-success ms-2">Verified Purchase</span>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        @if($comments->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $comments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-chat-left-text text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No reviews yet</h5>
                            <p class="text-muted">Be the first to review this product!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label.star {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    margin: 0 2px;
    transition: color 0.3s;
}

.rating-input label.star:hover,
.rating-input label.star:hover ~ label.star,
.rating-input input[type="radio"]:checked ~ label.star {
    color: #ffc107;
}

/* Disabled rating styles */
.rating-input-disabled {
    display: flex;
    justify-content: flex-start;
}

.rating-input-disabled .star-disabled {
    font-size: 1.5rem;
    margin: 0 2px;
    cursor: not-allowed;
}

.rating-input-disabled .star-disabled i {
    color: #dee2e6 !important;
}

/* Disabled card styles */
.card.opacity-75 {
    position: relative;
}

.card.opacity-75::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 1;
    border-radius: 0.375rem;
    pointer-events: none;
}

.card.opacity-75 .card-body {
    position: relative;
    z-index: 2;
}
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartForms = document.querySelectorAll('.add-to-cart-form');
        
        addToCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('.add-to-cart-btn');
                const formData = new FormData(this);
                
                // Show loading state
                const originalBtnText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                btn.disabled = true;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success animation
                        btn.innerHTML = '<i class="bi bi-check-lg"></i> Added!';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');
                        
                        // Update cart count if applicable
                        if (data.cartCount) {
                            const cartCountElement = document.querySelector('.cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = data.cartCount;
                            }
                        }
                        
                        // Reset button after delay
                        setTimeout(() => {
                            btn.innerHTML = originalBtnText;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-primary');
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        alert(data.message || 'Failed to add product to cart.');
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product to cart.');
                    btn.innerHTML = originalBtnText;
                    btn.disabled = false;
                });
            });
        });
    });
</script>
@endsection 