@extends('layouts.master')
@section('title', 'Checkout')
@section('content')
<div class="container py-4">
    <div class="checkout-progress mb-4 animate__animated animate__fadeIn" data-aos="fade-up">
        <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
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
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" data-aos="fade-up">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-bag-check me-2"></i>
                    <h3 class="mb-0">Order Summary</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3 animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mb-3 animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning mb-3 animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
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
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; border-radius: 4px;">
                                                        <i class="bi bi-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <span>{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item['price'], 2) }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td class="text-end">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Your Credits:</td>
                                    <td class="text-end fw-bold">${{ number_format($user->credits, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Balance After Purchase:</td>
                                    <td class="text-end fw-bold {{ $user->credits >= $total ? 'text-success' : 'text-danger' }}">
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
            <div class="card border-0 shadow-sm animate__animated animate__fadeIn" data-aos="fade-left">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-truck me-2"></i>
                    <h3 class="mb-0">Shipping Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.place') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback animate__animated animate__headShake">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <textarea name="billing_address" id="billing_address" class="form-control @error('billing_address') is-invalid @enderror" rows="3" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback animate__animated animate__headShake">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="save_address" name="save_address">
                                <label class="form-check-label" for="save_address">
                                    Save address for future orders
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if($user->credits >= $total)
                                <button type="submit" class="btn btn-success animate__animated animate__pulse animate__infinite animate__slow" id="complete-order-btn">
                                    <i class="bi bi-check-circle me-1"></i> Complete Order
                                </button>
                            @else
                                <div class="alert alert-danger animate__animated animate__headShake">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    You don't have enough credits to complete this purchase.
                                </div>
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="bi bi-lock me-1"></i> Insufficient Credits
                                </button>
                            @endif
                            <a href="{{ route('cart') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3 animate__animated animate__fadeIn" data-aos="fade-up">
                <div class="card-body">
                    <h5><i class="bi bi-shield-check me-2 text-success"></i>Secure Checkout</h5>
                    <p class="text-muted small">Your information is protected with industry-standard encryption.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Progress steps styling */
    .checkout-progress {
        margin-bottom: 2rem;
    }
    .progress-step {
        text-align: center;
        width: 33.333%;
        position: relative;
    }
    .step-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background-color: #e9ecef;
        border-radius: 50%;
        margin-bottom: 5px;
    }
    .progress-step.completed .step-icon {
        background-color: #28a745;
        color: white;
    }
    .progress-step.active .step-icon {
        background-color: #007bff;
        color: white;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }
</style>
@endpush

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
