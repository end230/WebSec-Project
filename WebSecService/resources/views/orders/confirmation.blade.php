@extends('layouts.master')
@section('title', 'Order Confirmation')
@section('content')
<div class="container py-5">
    <div class="checkout-progress mb-5 animate__animated animate__fadeIn" data-aos="fade-up">
        <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <div class="progress-step completed">
                <div class="step-icon"><i class="bi bi-cart-check"></i></div>
                <div class="step-label">Cart</div>
            </div>
            <div class="progress-step completed">
                <div class="step-icon"><i class="bi bi-credit-card"></i></div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="progress-step completed">
                <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInUp">
                <div class="card-body text-center p-5">
                    <div class="success-animation mb-4">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>

                    <h1 class="display-4 mb-3 fw-bold text-success">Thank You!</h1>
                    <p class="lead mb-4">Your order has been placed successfully.</p>

                    <div class="order-details mb-4 animate__animated animate__fadeIn animate__delay-1s">
                        <h5 class="text-muted mb-3">Order Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-receipt me-2"></i>Order Number</h6>
                                        <p class="card-text fw-bold">{{ $order->id ?? '#'.rand(10000, 99999) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-calendar me-2"></i>Order Date</h6>
                                        <p class="card-text fw-bold">{{ isset($order->created_at) ? $order->created_at->format('M d, Y') : date('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-credit-card me-2"></i>Payment Method</h6>
                                        <p class="card-text fw-bold">Credits</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-truck me-2"></i>Shipping Method</h6>
                                        <p class="card-text fw-bold">Standard Shipping</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shipping-info mb-4 animate__animated animate__fadeIn animate__delay-2s">
                        <h5 class="text-muted mb-3">Shipping Information</h5>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <p class="mb-0">{{ $order->shipping_address ?? '123 Main Street, Apt 4B, New York, NY 10001' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary animate__animated animate__fadeIn animate__delay-3s">
                        <h5 class="text-muted mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($order) && isset($order->items))
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="border-top">
                                            <td colspan="2" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>Product Example</td>
                                            <td>2</td>
                                            <td class="text-end">$24.99</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td colspan="2" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold">$49.98</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5 animate__animated animate__fadeIn animate__delay-3s">
                        <a href="{{ route('products_list') }}" class="btn btn-primary btn-lg me-2">
                            <i class="bi bi-shop me-2"></i>Continue Shopping
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-download me-2"></i>Download Receipt
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center animate__animated animate__fadeIn animate__delay-4s">
                <p class="text-muted">
                    <i class="bi bi-envelope me-1"></i> A confirmation email has been sent to your email address.
                </p>
                <p class="text-muted">
                    <i class="bi bi-question-circle me-1"></i>
                    Have questions about your order? <a href="#">Contact our support</a>
                </p>
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

    /* Checkmark animation */
    .success-animation {
        margin: 0 auto;
    }
    .checkmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #4bb71b;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px #4bb71b;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        position: relative;
        margin: 0 auto;
    }
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4bb71b;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #4bb71b;
        }
    }
</style>
@endpush
@endsection
