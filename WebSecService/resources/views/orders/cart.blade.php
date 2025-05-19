@extends("layouts.master")
@section("title", "Shopping Cart")
@section("content")
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">
                <i class="bi bi-cart3 me-2"></i>Your Shopping Cart
            </h2>
            <span class="badge bg-light text-primary">{{ count($cart) }} item(s)</span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning mb-3">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    @if(count($cart) > 0)
                        <div class="card-body p-0">
                            @foreach($cart as $id => $item)
                                <div class="card mb-3 border-0 shadow-sm cart-item animate__animated animate__fadeIn"
                                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="row g-0">
                                        @if(isset($item['photo']))
                                            <div class="col-md-2">
                                                <img src="{{ asset('storage/'.$item['photo']) }}" class="img-fluid rounded-start" alt="{{ $item['name'] }}"
                                                     style="height: 100%; object-fit: cover;">
                                            </div>
                                            <div class="col-md-10">
                                        @else
                                            <div class="col-md-2 d-flex align-items-center justify-content-center bg-light">
                                                <i class="bi bi-box text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                            <div class="col-md-10">
                                        @endif
                                            <div class="card-body d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title">{{ $item['name'] }}</h5>
                                                    <p class="card-text text-primary fw-bold">${{ number_format($item['price'], 2) }}</p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-4 text-center">
                                                        <span class="d-block text-muted small">Quantity</span>
                                                        <span class="fw-bold">{{ $item['quantity'] }}</span>
                                                    </div>
                                                    <div class="me-4 text-end">
                                                        <span class="d-block text-muted small">Subtotal</span>
                                                        <span class="fw-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                                    </div>
                                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm remove-item-btn">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('products_list') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                            </a>
                            <a href="{{ route('checkout') }}" class="btn btn-success {{ count($cart) > 0 ? '' : 'disabled' }} checkout-btn">
                                <i class="bi bi-credit-card me-1"></i> Proceed to Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5 animate__animated animate__fadeIn">
                            <div class="mb-4">
                                <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                            <h3>Your cart is empty</h3>
                            <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                            <a href="{{ route('products_list') }}" class="btn btn-primary mt-3 animate__animated animate__pulse animate__infinite animate__slow">
                                <i class="bi bi-bag-plus me-1"></i> Browse Products
                            </a>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" data-aos="fade-left">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Tax:</span>
                                <span>$0.00</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5>${{ number_format($total, 2) }}</h5>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <h5>Your Credits:</h5>
                                <h5>${{ number_format($user->credits, 2) }}</h5>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <h5>Balance After Purchase:</h5>
                                <h5 class="{{ $user->credits >= $total ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($user->credits - $total, 2) }}
                                </h5>
                            </div>

                            @if($user->credits < $total)
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    You don't have enough credits for this purchase.
                                </div>
                            @endif

                            <div class="d-grid mt-4">
                                @if(count($cart) > 0)
                                    <a href="{{ route('checkout') }}" class="btn btn-primary {{ $user->credits < $total ? 'disabled' : '' }}">
                                        <i class="bi bi-credit-card me-1"></i> Proceed to Checkout
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-3" data-aos="fade-up">
                        <div class="card-body">
                            <h5 class="mb-3">Need Help?</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-question-circle me-2 text-primary"></i>
                                    <a href="#" class="text-decoration-none">Shipping Policy</a>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-arrow-return-left me-2 text-primary"></i>
                                    <a href="#" class="text-decoration-none">Returns & Refunds</a>
                                </li>
                                <li>
                                    <i class="bi bi-headset me-2 text-primary"></i>
                                    <a href="#" class="text-decoration-none">Contact Support</a>
                                </li>
                            </ul>
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
        // Animation when checking out
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                const cartItems = document.querySelectorAll('.cart-item');

                cartItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('animate__fadeOutRight');
                    }, index * 100);
                });

                // Allow animation to complete before navigating
                e.preventDefault();
                setTimeout(() => {
                    window.location.href = checkoutBtn.getAttribute('href');
                }, cartItems.length * 100 + 300);
            });
        }

        // Animation when removing items
        const removeBtns = document.querySelectorAll('.remove-item-btn');
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form');
                const cartItem = this.closest('.cart-item');

                cartItem.classList.add('animate__fadeOutRight');

                cartItem.addEventListener('animationend', () => {
                    form.submit();
                });
            });
        });
    });
</script>
@endpush

@endsection
