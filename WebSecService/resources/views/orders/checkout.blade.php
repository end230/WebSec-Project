@extends('layouts.master')
@section('title', 'Checkout')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="checkout-progress mb-4 animate__animated animate__fadeIn" data-aos="fade-up">
        <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-tea" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <div class="progress-step completed">
                <div class="step-icon"><i class="bi bi-cart-check"></i></div>
                <div class="step-label">Cart</div>
            </div>
            <div class="progress-step active">
                <div class="step-icon"><i class="bi bi-credit-card"></i></div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="progress-step">
                <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="tea-admin-card mb-4 animate__animated animate__fadeIn" data-aos="fade-up">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-bag-check me-2"></i>
                    <h3 class="mb-0">Order Summary</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-tea animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-tea-warning animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table tea-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr class="order-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($item['photo']))
                                                    <img src="{{ asset('storage/'.$item['photo']) }}" alt="{{ $item['name'] }}" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <div class="bg-tea-50 d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; border-radius: 4px;">
                                                        <i class="bi bi-box text-tea"></i>
                                                    </div>
                                                @endif
                                                <span class="text-tea-800">{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-tea-700">${{ number_format($item['price'], 2) }}</td>
                                        <td class="text-tea-700">{{ $item['quantity'] }}</td>
                                        <td class="text-end text-tea-700">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top border-tea-200">
                                    <td colspan="3" class="text-end fw-bold text-tea-800">Total:</td>
                                    <td class="text-end fw-bold text-tea-800">${{ number_format($total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-tea-800">Your Credits:</td>
                                    <td class="text-end fw-bold text-tea-800">${{ number_format($user->credits, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-tea-800">Balance After Purchase:</td>
                                    <td class="text-end fw-bold {{ $user->credits >= $total ? 'text-tea-success' : 'text-tea-danger' }}">
                                        ${{ number_format($user->credits - $total, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="tea-admin-card animate__animated animate__fadeIn" data-aos="fade-left">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-truck me-2"></i>
                    <h3 class="mb-0">Shipping Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.place') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label for="shipping_address" class="tea-form-label">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="tea-form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback animate__animated animate__headShake">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="tea-form-label">Billing Address</label>
                            <textarea name="billing_address" id="billing_address" class="tea-form-control @error('billing_address') is-invalid @enderror" rows="3" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback animate__animated animate__headShake">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="tea-form-check">
                                <input class="tea-form-check-input" type="checkbox" id="save_address" name="save_address">
                                <label class="tea-form-check-label" for="save_address">
                                    Save address for future orders
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if($user->credits >= $total)
                                <button type="submit" class="tea-btn tea-btn-primary animate__animated animate__pulse animate__infinite animate__slow" id="complete-order-btn">
                                    <i class="bi bi-check-circle me-1"></i> Complete Order
                                </button>
                            @else
                                <div class="alert alert-tea-danger animate__animated animate__headShake">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    You don't have enough credits to complete this purchase.
                                </div>
                                <button type="button" class="tea-btn tea-btn-secondary" disabled>
                                    <i class="bi bi-lock me-1"></i> Insufficient Credits
                                </button>
                            @endif
                            <a href="{{ route('cart') }}" class="tea-btn tea-btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .checkout-progress {
        margin-bottom: 2rem;
    }

    .progress-step {
        text-align: center;
    flex: 1;
        position: relative;
    }

    .step-icon {
    width: 40px;
    height: 40px;
    background: var(--tea-green-100);
    border-radius: 50%;
    display: flex;
        align-items: center;
        justify-content: center;
    margin: 0 auto 0.5rem;
    color: var(--tea-green-600);
}

    .progress-step.completed .step-icon {
    background: var(--tea-green-600);
        color: white;
    }

    .progress-step.active .step-icon {
    background: var(--tea-green-500);
        color: white;
    animation: pulse 2s infinite;
}

.step-label {
    color: var(--tea-green-700);
    font-size: 0.9rem;
}

.progress-step.completed .step-label {
    color: var(--tea-green-800);
    font-weight: 600;
}

.progress-step.active .step-label {
    color: var(--tea-green-800);
    font-weight: 600;
}

.bg-tea {
    background-color: var(--tea-green-500);
}

.tea-table thead {
    background-color: var(--tea-green-50);
}

.tea-table th {
    color: var(--tea-green-700);
    font-weight: 600;
    border-bottom-color: var(--tea-green-200);
}

.tea-form-label {
    color: var(--tea-green-700);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tea-form-control {
    border: 1px solid var(--tea-green-200);
    border-radius: 0.5rem;
    padding: 0.75rem;
    transition: all 0.2s;
}

.tea-form-control:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
}

.tea-form-check {
    padding-left: 1.75rem;
}

.tea-form-check-input {
    border-color: var(--tea-green-400);
}

.tea-form-check-input:checked {
    background-color: var(--tea-green-500);
    border-color: var(--tea-green-500);
}

.tea-form-check-label {
    color: var(--tea-green-700);
}

.alert-tea {
    background-color: var(--tea-green-50);
    border-color: var(--tea-green-200);
    color: var(--tea-green-700);
}

.alert-tea-warning {
    background-color: var(--tea-yellow-50);
    border-color: var(--tea-yellow-200);
    color: var(--tea-yellow-700);
}

.alert-tea-danger {
    background-color: var(--tea-red-50);
    border-color: var(--tea-red-200);
    color: var(--tea-red-700);
}

.text-tea-700 {
    color: var(--tea-green-700);
}

.text-tea-800 {
    color: var(--tea-green-800);
}

.text-tea-success {
    color: var(--tea-green-600);
}

.text-tea-danger {
    color: var(--tea-red-600);
}

.bg-tea-50 {
    background-color: var(--tea-green-50);
}

.border-tea-200 {
    border-color: var(--tea-green-200);
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

    @keyframes pulse {
        0% {
        box-shadow: 0 0 0 0 rgba(var(--tea-green-rgb), 0.4);
        }
        70% {
        box-shadow: 0 0 0 10px rgba(var(--tea-green-rgb), 0);
        }
        100% {
        box-shadow: 0 0 0 0 rgba(var(--tea-green-rgb), 0);
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle complete order animation
        const completeOrderBtn = document.getElementById('complete-order-btn');
        if (completeOrderBtn) {
            completeOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Add animations to order items
                const orderItems = document.querySelectorAll('.order-item');
                orderItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('animate__animated', 'animate__fadeOutLeft');
                    }, index * 100);
                });

                // Show loading spinner by changing button text/icon
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing Order...';
                this.disabled = true;
                this.classList.remove('animate__infinite');

                // Submit the form after animations
                setTimeout(() => {
                    document.getElementById('checkout-form').submit();
                }, orderItems.length * 100 + 500);
            });
        }

        // Address field animation
        const addressFields = document.querySelectorAll('textarea');
        addressFields.forEach(field => {
            field.addEventListener('focus', function() {
                this.classList.add('animate__animated', 'animate__pulse');
                this.addEventListener('animationend', function() {
                    this.classList.remove('animate__animated', 'animate__pulse');
                }, {once: true});
            });
        });
    });
</script>
@endpush
@endsection
