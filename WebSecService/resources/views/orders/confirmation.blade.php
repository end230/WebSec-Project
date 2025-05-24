@extends('layouts.master')
@section('title', 'Order Confirmation')
@include('layouts.admin-theme')

@section('content')
<div class="container py-5">
    <div class="checkout-progress mb-5 animate__animated animate__fadeIn" data-aos="fade-up">
        <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-tea" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
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
        <div class="col-md-8">
            <div class="tea-admin-card animate__animated animate__fadeIn">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header text-center">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-tea" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="mb-0 text-tea-800">Order Confirmed!</h2>
                    <p class="text-tea-600 mt-2">Thank you for your order. We'll start processing it right away.</p>
                </div>

                <div class="card-body">
                    <div class="order-details mb-4 animate__animated animate__fadeIn animate__delay-1s">
                        <h5 class="text-tea-700 mb-3">Order Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="tea-info-card">
                                    <div class="card-body">
                                        <h6 class="card-title text-tea-700"><i class="bi bi-receipt me-2"></i>Order Number</h6>
                                        <p class="card-text text-tea-800 fw-bold">{{ $order->id ?? '#'.rand(10000, 99999) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="tea-info-card">
                                    <div class="card-body">
                                        <h6 class="card-title text-tea-700"><i class="bi bi-calendar me-2"></i>Order Date</h6>
                                        <p class="card-text text-tea-800 fw-bold">{{ isset($order->created_at) ? $order->created_at->format('M d, Y') : date('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="tea-info-card">
                                    <div class="card-body">
                                        <h6 class="card-title text-tea-700"><i class="bi bi-credit-card me-2"></i>Payment Method</h6>
                                        <p class="card-text text-tea-800 fw-bold">Credits</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="tea-info-card">
                                    <div class="card-body">
                                        <h6 class="card-title text-tea-700"><i class="bi bi-truck me-2"></i>Shipping Method</h6>
                                        <p class="card-text text-tea-800 fw-bold">Standard Shipping</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shipping-info mb-4 animate__animated animate__fadeIn animate__delay-2s">
                        <h5 class="text-tea-700 mb-3">Shipping Information</h5>
                        <div class="tea-info-card">
                            <div class="card-body">
                                <p class="mb-0 text-tea-800">{{ $order->shipping_address ?? '123 Main Street, Apt 4B, New York, NY 10001' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary animate__animated animate__fadeIn animate__delay-3s">
                        <h5 class="text-tea-700 mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table tea-table">
                                <thead>
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
                                                <td class="text-tea-800">{{ $item->product->name }}</td>
                                                <td class="text-tea-700">{{ $item->quantity }}</td>
                                                <td class="text-end text-tea-700">${{ number_format($item->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="border-top border-tea-200">
                                            <td colspan="2" class="text-end fw-bold text-tea-800">Total:</td>
                                            <td class="text-end fw-bold text-tea-800">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('orders.index') }}" class="tea-btn tea-btn-primary">
                            <i class="bi bi-list-ul me-1"></i> View All Orders
                        </a>
                        <a href="{{ route('products_list') }}" class="tea-btn tea-btn-secondary ms-2">
                            <i class="bi bi-cart-plus me-1"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-tea {
    color: var(--tea-green-600);
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

.bg-tea {
    background-color: var(--tea-green-500);
}

.tea-info-card {
    background-color: var(--tea-green-50);
    border-radius: 0.5rem;
    border: 1px solid var(--tea-green-100);
    transition: all 0.2s;
}

.tea-info-card:hover {
    border-color: var(--tea-green-200);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.tea-table thead {
    background-color: var(--tea-green-50);
}

.tea-table th {
    color: var(--tea-green-700);
    font-weight: 600;
    border-bottom-color: var(--tea-green-200);
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

.step-label {
    color: var(--tea-green-700);
    font-size: 0.9rem;
}

.progress-step.completed .step-label {
    color: var(--tea-green-800);
    font-weight: 600;
}
</style>
@endsection
