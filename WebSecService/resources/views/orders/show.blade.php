@extends('layouts.master')
@section('title', 'Order Details')
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
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Order #{{ $order->id }}</h3>
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 
                                          ($order->status == 'delivered' ? 'success' : 
                                          ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Customer Feedback Alert -->
                @if(isset($order->feedback) && $order->feedback->count() > 0)
                <div class="alert alert-info border-left border-info m-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-chat-quote me-2 fs-4"></i>
                        <div>
                            <strong>Customer Feedback Available</strong>
                            <p class="mb-0 small">This order has {{ $order->feedback->count() }} feedback submission(s)</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Order Date</h5>
                            <p>{{ $order->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer</h5>
                            <p>{{ $order->user->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Shipping Address</h5>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Billing Address</h5>
                            <p>{{ $order->billing_address }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                    <td>{{ $item->product->name }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Feedback Section -->
            @if(isset($order->feedback) && $order->feedback->count() > 0)
            <div class="card mb-4 animate__animated animate__fadeIn">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0"><i class="bi bi-chat-square-text me-2"></i>Feedback Information</h3>
                </div>
                <div class="card-body">
                    @foreach($order->feedback as $feedback)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="mb-1">
                                @if($feedback->cancellation_type == 'employee')
                                    <span class="badge bg-danger me-2">Administrative Cancellation</span>
                                @else
                                    <span class="badge bg-warning me-2">Customer Cancellation</span>
                                @endif
                                {{ $feedback->isEmployeeCancellation() ? 
                                    (App\Models\Feedback::getEmployeeReasons()[$feedback->reason] ?? $feedback->reason) : 
                                    (App\Models\Feedback::getReasons()[$feedback->reason] ?? $feedback->reason) }}
                            </h5>
                            <span class="text-muted small">{{ $feedback->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        
                        @if($feedback->isCustomerCancellation() && $feedback->comments)
                            <div class="card bg-light mb-3">
                                <div class="card-body py-2">
                                    <strong>Customer Comments:</strong>
                                    <p class="mb-0">{{ $feedback->comments }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($feedback->isEmployeeCancellation() && $feedback->staff_notes && Auth::user()->hasPermissionTo('manage_orders'))
                            <div class="card bg-light mb-3 border-danger">
                                <div class="card-header bg-danger bg-opacity-10 text-danger">
                                    <strong><i class="bi bi-eye-slash me-1"></i> Internal Staff Notes (Not visible to customer)</strong>
                                </div>
                                <div class="card-body py-2">
                                    <p class="mb-0">{{ $feedback->staff_notes }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Feedback Status -->
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="badge bg-{{ $feedback->resolved ? 'success' : 'warning' }}">
                                {{ $feedback->resolved ? 'Resolved' : 'Pending' }}
                            </span>
                            
                            @if(Auth::user()->hasPermissionTo('respond_to_feedback') && !$feedback->resolved)
                            <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-reply"></i> Respond
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Order Actions</h3>
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
                            <a href="{{ route('orders.cancel.form', $order->id) }}" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancel Order
                            </a>
                        @endif
                        
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
                        <a href="{{ route('products_list') }}" class="btn btn-success">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
