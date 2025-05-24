@extends('layouts.master')
@section('title', 'Order Details')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="tea-admin-card mb-4">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Order #{{ $order->id }}
                        <span class="tea-status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="text-tea-800">Order Date</h5>
                            <p class="text-tea-700">{{ $order->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-tea-800">Customer</h5>
                            <p class="text-tea-700">{{ $order->user->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-tea-800">Shipping Address</h5>
                            <p class="text-tea-700">{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-tea-800">Billing Address</h5>
                            <p class="text-tea-700">{{ $order->billing_address }}</p>
                        </div>
                    </div>

                    <h5 class="text-tea-800 mb-3">Order Items</h5>
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
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="text-tea-800">{{ $item->product->name }}</td>
                                    <td class="text-tea-700">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-tea-700">{{ $item->quantity }}</td>
                                    <td class="text-end text-tea-700">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="border-top border-tea-200">
                                    <td colspan="3" class="text-end fw-bold text-tea-800">Total:</td>
                                    <td class="text-end fw-bold text-tea-800">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Feedback Section -->
            @if(isset($order->feedback) && $order->feedback->count() > 0)
                <div class="tea-admin-card mb-4">
                    <div class="tea-steam">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                        @endfor
                    </div>
                    <div class="card-header">
                        <h4 class="mb-0 text-tea-800">
                            <i class="bi bi-chat-dots me-2"></i>Customer Feedback
                        </h4>
                    </div>
                    <div class="card-body">
                        @foreach($order->feedback as $feedback)
                            <div class="tea-feedback-card mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="text-tea-800 mb-0">{{ $feedback->user->name }}</h6>
                                            <span class="text-tea-600">{{ $feedback->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-tea-700 mb-2">{{ $feedback->comment }}</p>
                                        <div class="tea-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $feedback->rating ? '-fill' : '' }} {{ $i <= $feedback->rating ? 'text-tea' : 'text-tea-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="tea-admin-card">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header">
                    <h4 class="mb-0 text-tea-800">
                        <i class="bi bi-gear me-2"></i>Order Actions
                    </h4>
                </div>
                <div class="card-body">
                    @if(Auth::user()->hasAnyRole(['Admin', 'Employee']))
                    <form action="{{ route('orders.update.status', $order->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                    @endif

                    <div class="d-grid gap-2">
                        <!-- Add cancel button for customers (if order is pending or processing) -->
                        @if((Auth::id() == $order->user_id || Auth::user()->hasPermissionTo('manage_orders')) && in_array($order->status, ['pending', 'processing']))
                            <a href="{{ route('orders.cancel.form', $order->id) }}" class="tea-btn tea-btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancel Order
                            </a>
                        @endif
                        
                        <a href="{{ route('orders.index') }}" class="tea-btn tea-btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Orders
                        </a>
                        <a href="{{ route('products_list') }}" class="tea-btn tea-btn-primary">
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

.text-tea-200 {
    color: var(--tea-green-200);
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

.tea-feedback-card {
    background-color: var(--tea-green-50);
    border-radius: 0.5rem;
    padding: 1rem;
    border: 1px solid var(--tea-green-100);
    transition: all 0.2s;
}

.tea-feedback-card:hover {
    border-color: var(--tea-green-200);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.tea-rating {
    font-size: 1.1rem;
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

.tea-status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.tea-status-badge.pending {
    background-color: var(--tea-yellow-50);
    color: var(--tea-yellow-700);
}

.tea-status-badge.processing {
    background-color: var(--tea-blue-50);
    color: var(--tea-blue-700);
}

.tea-status-badge.completed {
    background-color: var(--tea-green-50);
    color: var(--tea-green-700);
}

.tea-status-badge.cancelled {
    background-color: var(--tea-red-50);
    color: var(--tea-red-700);
}
</style>
@endsection
