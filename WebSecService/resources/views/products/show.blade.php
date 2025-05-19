@extends('layouts.master')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
</div>
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