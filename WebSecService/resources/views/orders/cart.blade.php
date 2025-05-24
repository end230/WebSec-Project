@php use Illuminate\Support\Str; @endphp
@extends("layouts.master")
@section("title", "Shopping Cart")
@include('layouts.admin-theme')

@section("content")
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="tea-steam">
            @for($i = 1; $i <= 3; $i++)
                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
            @endfor
        </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-cart3 me-2"></i>Your Shopping Cart
                <span class="badge bg-tea-light text-tea">{{ count($cart) }} {{ Str::plural('item', count($cart)) }}</span>
            </h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-tea animate__animated animate__fadeIn">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-tea-danger animate__animated animate__fadeIn">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning animate__animated animate__fadeIn mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="table-responsive">
                    <table class="table tea-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                                <tr class="cart-item animate__animated animate__fadeIn" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(isset($item['photo']))
                                                <img src="{{ asset('storage/'.$item['photo']) }}" alt="{{ $item['name'] }}" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="bg-tea-50 d-flex align-items-center justify-content-center me-2" style="width: 50px; height: 50px; border-radius: 8px;">
                                                    <i class="bi bi-box text-tea"></i>
                                                </div>
                                            @endif
                                            <span class="text-tea-800">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-tea-700">${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <div class="tea-quantity-control">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="tea-btn tea-btn-sm tea-btn-quantity" onclick="decrementQuantity(this)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="tea-quantity-input">
                                                <button type="button" class="tea-btn tea-btn-sm tea-btn-quantity" onclick="incrementQuantity(this)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end text-tea-700">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tea-btn tea-btn-sm tea-btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="border-top border-tea-200">
                                <td colspan="3" class="text-end fw-bold text-tea-800">Total:</td>
                                <td class="text-end fw-bold text-tea-800">${{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('products_list') }}" class="tea-btn tea-btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                    <a href="{{ route('checkout') }}" class="tea-btn tea-btn-primary animate__animated animate__pulse animate__infinite animate__slow">
                        <i class="bi bi-credit-card me-1"></i> Proceed to Checkout
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-cart-animation mb-4">
                        <i class="bi bi-cart-x text-tea-300" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-tea-800 mb-3">Your cart is empty</h4>
                    <p class="text-tea-600 mb-4">Add some products to your cart and start shopping!</p>
                    <a href="{{ route('products_list') }}" class="tea-btn tea-btn-primary">
                        <i class="bi bi-shop me-1"></i> Browse Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.text-tea {
    color: var(--tea-green-600);
}

.text-tea-300 {
    color: var(--tea-green-300);
}

.text-tea-600 {
    color: var(--tea-green-600);
}

.text-tea-700 {
    color: var(--tea-green-700);
}

.text-tea-800 {
    color: var(--tea-green-800);
}

.bg-tea-50 {
    background-color: var(--tea-green-50);
}

.bg-tea-light {
    background-color: var(--tea-green-100);
}

.border-tea-200 {
    border-color: var(--tea-green-200);
}

.tea-table thead {
    background-color: var(--tea-green-50);
}

.tea-table th {
    color: var(--tea-green-700);
    font-weight: 600;
    border-bottom-color: var(--tea-green-200);
}

.tea-quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tea-quantity-input {
    width: 60px;
    text-align: center;
    border: 1px solid var(--tea-green-200);
    border-radius: 0.25rem;
    padding: 0.25rem;
    color: var(--tea-green-700);
}

.tea-quantity-input:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
    outline: none;
}

.tea-btn-quantity {
    background-color: var(--tea-green-50);
    border: 1px solid var(--tea-green-200);
    color: var(--tea-green-700);
    padding: 0.25rem 0.5rem;
    transition: all 0.2s;
}

.tea-btn-quantity:hover {
    background-color: var(--tea-green-100);
    border-color: var(--tea-green-300);
}

.alert-tea {
    background-color: var(--tea-green-50);
    border-color: var(--tea-green-200);
    color: var(--tea-green-700);
}

.alert-tea-danger {
    background-color: var(--tea-red-50);
    border-color: var(--tea-red-200);
    color: var(--tea-red-700);
}

.empty-cart-animation {
    animation: float 3s ease-in-out infinite;
}

.tea-steam {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 30px;
    overflow: hidden;
    opacity: 0.7;
}

.steam {
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 2px;
    height: 15px;
    background: var(--tea-green-200);
    border-radius: 10px;
    animation: steam 2s infinite;
    transform-origin: bottom;
    opacity: 0;
}

.steam:nth-child(1) {
    left: 25%;
    animation-delay: var(--delay);
}

.steam:nth-child(2) {
    left: 50%;
    animation-delay: calc(var(--delay) + 0.3s);
}

.steam:nth-child(3) {
    left: 75%;
    animation-delay: calc(var(--delay) + 0.6s);
}

@keyframes steam {
    0% {
        transform: translateY(0) scaleX(1);
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    50% {
        transform: translateY(-10px) scaleX(3);
    }
    95% {
        opacity: 0;
    }
    100% {
        transform: translateY(-20px) scaleX(4);
        opacity: 0;
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>

@push('scripts')
<script>
function incrementQuantity(button) {
    const input = button.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    input.closest('form').submit();
}

function decrementQuantity(button) {
    const input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        input.closest('form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect to cart items
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--tea-green-50)';
            this.style.transition = 'background-color 0.2s';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
@endpush

@endsection
